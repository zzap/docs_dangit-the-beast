<?php

declare(strict_types=1);

namespace Docsdangit\Backend\Repository;

use DateTimeZone;
use Docsdangit\Backend\Entity\CodeBlock;
use Docsdangit\Backend\Entity\DocsEntry;
use Docsdangit\Backend\Serialize\DatabaseEntryToDocsEntry;
use Docsdangit\Backend\Service\Entity;
use Docsdangit\Backend\Service\Repository as RepositoryInterface;
use PDO;
use function hash;
use function json_encode;
use function sprintf;

final class MySQL implements RepositoryInterface
{
	public function __construct(
		private PDO $dbConnection,
		private DatabaseEntryToDocsEntry $dbToDocs,
	) {}

	public function cleanup(string $url): void
	{
		$hash = hash('sha512', $url);
		$query = $this->dbConnection->prepare('DELETE FROM docentries WHERE entryhash = :hash');
		$query->execute([
			'hash' => $hash,
		]);
	}

	public function store(Entity $entity) : void
	{
		assert($entity instanceof DocsEntry);

		$query = <<<'SQL'
INSERT INTO docentries (entryhash, searchcontent, object)
VALUES (:hash, :searchcontent, :object)
SQL;
		$codecontent = $entity->codeBlock->code;

		$commandTags = [];
		foreach ($entity->commandTags as $tag) {
			$commandTags[] = (string) $tag;
		}

		$tags = [];
		foreach ($entity->tags as $tag) {
			$tags[] = (string) $tag;
		}

		$object = [
			'code_snippet' => [[
				'code' => $entity->codeBlock->code,
				'language' => $entity->codeBlock->language,
			]],
			'code' => $entity->codeBlock->code,
			'code_language' => $entity->codeBlock->language,
			'parse_date' => $entity->parseDate->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
			'url' => (string) $entity->url,
			'code_creator' => (string) $entity->codeCreator,
			'code_creation_datetime' => $entity->codeCreationDateTime->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
			'source' => (string) $entity->docsSource,
			'version' => (string) $entity->docsVersion,
			'command_tags' => $commandTags,
			'tags' => $tags,
			'language' => (string) $entity->language,
			'function' => (string) $entity->docsFunction,
		];

		$query = $this->dbConnection->prepare($query);
		$query->execute([
			'hash' => hash('sha512', (string) $entity->url),
			'searchcontent' => $codecontent,
			'object' => json_encode($object),
		]);
	}

	public function fetch(string|null $search, int $limit, int $offset, array $filters): array
	{
		$query = 'SELECT *, MATCH(searchcontent) AGAINST (:search IN BOOLEAN MODE) as score FROM docentries';
		$filterQuery = ['MATCH(searchcontent) AGAINST (:search IN BOOLEAN MODE)'];
		$params = [
			'search' => $search . '*',
		];


		if ($search === null) {
			$query = 'SELECT *, 1 as score FROM docentries';
			unset($params['search']);
			$filterQuery = [];
		}

		if ($filters !== []) {
			if (isset($filters['tag'])) {
				foreach ($filters['tag'] as $tag) {
					$filterQuery[] = '"' . $tag . '" MEMBER OF(object->>\'$.tags\')';
				}
			}
			if (isset($filters['command'])) {
				foreach ($filters['command'] as $tag) {
					$filterQuery[] = '"' . $tag . '" MEMBER OF(object->>\'$.command_tags\')';
				}
			}
			if (isset($filters['function'])) {
				$filterQuery[] = 'object->>\'$.function\' = "' . $filters['function'] . '"';
			}
		}

		if ($filterQuery !== []) {
			$query .= ' WHERE ' . implode(' AND ', $filterQuery);
		}

		$query .= ' ORDER BY score DESC LIMIT %1$d, %2$d';

		$query = $this->dbConnection->prepare(sprintf($query, $offset, $limit));

		$result = $query->execute($params);

		$resultList = [];
		foreach ($query->fetchAll() as $entry) {
			$resultList[] = $this->dbToDocs->unserialize($entry);
		}

		return $resultList;
	}

	public function fetchSingle(string $hash): Entity
	{
		$query = $this->dbConnection->prepare( 'SELECT * FROM docentries WHERE id = :hash');

		$query->execute(['hash' => $hash]);

		return $this->dbToDocs->unserialize($query->fetch());
	}

}
