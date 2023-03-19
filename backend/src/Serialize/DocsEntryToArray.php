<?php
declare(strict_types=1);

namespace Docsdangit\Backend\Serialize;

use DateTimeZone;
use Docsdangit\Backend\Entity\CodeBlock;
use Docsdangit\Backend\Entity\DocsEntry;

final class DocsEntryToArray
{
	public function serialize(DocsEntry $entry): array
	{
		$content = [
			'code_snippet' => [],
			'command_tags' => [],
			'tags' => [],
			'parse_date' => $entry->parseDate->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
			'url' => (string) $entry->url,
			'code_creator' => (string) $entry->codeCreator,
			'code_creation_datetime' => (string) $entry->codeCreationDateTime->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
			'source' => (string) $entry->docsSource,
			'version' => (string) $entry->docsVersion,
			'function' => (string) $entry->docsFunction,
			'language' => (string) $entry->language,
        ];

		/** @var CodeBlock $block */
		foreach ($entry->codeBlocks as $block) {
			$content['code_snippet'][] = [
				'code' => $block->code,
				'language' => $block->language,
			];
		}

		foreach ($entry->commandTags as $tag) {
			$content['command_tags'][] = (string) $tag;
		}

		foreach ($entry->tags as $tag) {
			$content['tags'][] = (string) $tag;
		}

		return $content;
	}
}
