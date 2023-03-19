<?php

declare(strict_types=1);

namespace Docsdangit\Backend\Repository;

use DateTimeZone;
use Docsdangit\Backend\Entity\CodeBlock;
use Docsdangit\Backend\Entity\DocsEntry;
use Docsdangit\Backend\Service\Entity;
use Docsdangit\Backend\Service\Repository as RepositoryInterface;
use PDO;
use function json_encode;

final class MySQL implements RepositoryInterface
{
	public function __construct(
		private PDO $dbConnection,
	) {}

	public function store(Entity $entity) : void
	{
		assert($entity instanceof DocsEntry);
		$query = <<<'SQL'
INSERT INTO docentries (id, searchcontent, object)
VALUES (:hash, :searchcontent, :object)
ON DUPLICATE KEY UPDATE
    searchcontent = :searchcontent,
    object = :object;
SQL;

		$codecontent = '';
		/** @var CodeBlock $block */
		foreach ($entity->codeBlocks as $block) {
			$codecontent .= $block->code;
		}

		$commandTags = [];
		foreach ($entity->commandTags as $tag) {
			$commandTags[] = (string) $tag;
		}

		$tags = [];
		foreach ($entity->tags as $tag) {
			$tags[] = (string) $tag;
		}

		$object = [
			'code_snippet' => [],
			'parse_date' => $entity->parseDate->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
			'url' => (string) $entity->url,
			'code_creator' => (string) $entity->codeCreator,
			'code_creation_datetime' => $entity->codeCreationDateTime->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
			'source' => $entity->docsSource,
			'version' => $entity->docsVersion,
			'command_tags' => $commandTags,
			'tags' => $tags,
			'language' => (string) $entity->language
		];

		$query = $this->dbConnection->prepare($query);
		$query->execute([
			'hash' => hash('sha512', (string) $entity->url),
			'searchcontent' => $codecontent,
			'object' => json_encode($object)
		]);
	}
}
