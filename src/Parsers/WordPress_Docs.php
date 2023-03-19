<?php
/**
 * WP Docs Parser
 *
 * @package Docsdangit
 */
namespace Docsdangit\Parsers;

use Docsdangit\Interfaces\Parser;
use Docsdangit\Data\Snippet;
use Docsdangit\Data\Plaintext;

class WordPress_Docs implements Parser {
    public function __construct() {}

    public function parse() {
        $url = 'https://developer.wordpress.org/wp-json/wp/v2/comments?per_page=100&page=';
        $headers = get_headers( $url . '1', true );
        $total_pages = $headers['X-WP-TotalPages'] ?? 41;
        for( $i = 1; $i <= $total_pages; $i += 1 ) {
            $raw = file_get_contents( $url . $i  );
            $json = json_decode( $raw );
            foreach( $json as $index => $item ) {
                $snippet = $this->parse_snippet( $item );
                $plainText = new Plaintext( $snippet, "dumps/{$item->id}.txt");
                $plainText->write();
            }
        }
    }

    public function parse_snippet( $item ) : Snippet {
        // parse snippet
        $id = hash( 'sha256', $item->link );
        $pattern = '/<code[^>]*lang="([^"]*)"[^>]*>(.*?)<\/code>/s';
        preg_match_all( $pattern, $item->content->rendered, $matches );
        $code_snippets = [];
        $language_tags = [];
        if( count( $matches ) > 1 ) {
            foreach( $matches[1] as $index => $match ) {
                $code_snippets[] = [
                    'language' => $match,
                    'code' => $matches[2][$index]
                ];
                $language_tags[] = $match;
            }
        }

        // get command tags
        $command_tags = [];

        $now = date( 'Y-m-d H:i:s' );
        $snippet_data = [
            'id' => $id,
            'snippet' => $code_snippets,
            'context' => $item->content->rendered,
            'source' => 'reference',
            'tags' => ['WordPress'],
            'command_tags' => $command_tags,
            'code_language_tags' => ['php'],
            'language' => 'en-US',
            'version' => 1,
            'url' => $item->link,
            'creator' => $item->author_name,
            'parse_date' => $now,
            'code_creation_date' => $item->date,
            'updated' => $now
        ];

        $snippet = new Snippet( ...$snippet_data );
        return $snippet;
    }
}
