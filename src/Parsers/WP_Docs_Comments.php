<?php
/**
 * WP_CLI parser
 *
 * @package Docsdangit
 */

namespace Docsdangit\Parsers;

use Docsdangit\Data\Snippet;

class WP_Docs_Comments implements ParserInterface
{
    public function __construct()
    {
    }

    public function parse(array $data): array
    {

        $snippets = [];
        foreach ($data as $snippet) {
            print_r($snippet);
            $snippets[] = $this->parse_snippet($snippet);
        }
        return $snippets;
    }

    private function parse_snippet($snippet): Snippet
    {
        $path = $snippet['link'];
        $id = hash('sha256', $path);
        $long_desc = $snippet->longdesc;
        $pattern = "/## EXAMPLES(.*?)##/s";
        preg_match($pattern, $long_desc, $matches);
        $code_snippet = count($matches) > 1 ? $matches[1] : '';

        $command_tags = [];

        $now = date('Y-m-d H:i:s');
        $snippet_data = [
            'id' => $id,
            'snippet' => $code_snippet,
            'context' => '',
            'source' => 'wp-cli',
            'tags' => ['WordPress'],
            'command_tags' => $command_tags,
            'code_language_tags' => ['php'],
            'language' => 'english',
            'version' => 1,
            'url' => $path,
            'creator' => '',
            'parse_date' => $now,
            'code_creation_date' => '',
            'updated' => $now
        ];
        return new Snippet(...$snippet_data);
    }
}
