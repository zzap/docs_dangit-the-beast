<?php
/**
 * WP_CLI parser
 *
 * @package Docsdangit
 */
namespace Docsdangit\Parsers;

use Docsdangit\Interfaces\Parser;
use Docsdangit\Data\Snippet;
use Docsdangit\Data\Plaintext;

class WP_CLI implements Parser {
    private $wp_cli_version;

    public function __construct() {
        $this->wp_cli_version = $this->get_source_version();
    }

    public function parse() {
        $file = 'data/wpcli-commands.json';
        $raw = file_get_contents( $file );
        $json = json_decode( $raw );
        $this->process_subcommands( $json->subcommands, 'https://developer.wordpress.org/cli/commands/' );
    }

    public function get_source_version() {
        $file = 'data/wpcli-version.txt';
        $raw = file_get_contents( $file );
        return str_replace("WP-CLI ", "", $raw);
    }

    public function reset() {}

    private function process_subcommands( $json, $path ) {
        foreach( $json as $item ) {
            $item_path = $path . $item->name . '/';
            $snippet = $this->parse_snippet( $item, $item_path );
            $plainText = new Plaintext( $snippet, "dumps/wp-cli-{$item->name}.txt");
            $plainText->write();
            // subcommands
            if( isset( $item->subcommands ) ) {
                $this->process_subcommands( $item->subcommands, $item_path );
            }
        }
    }

    private function parse_snippet( $item, $path ) {
        // parse code snippet
        $id = hash( 'sha256', $path );
        $long_desc = $item->longdesc;
        $pattern = "/## EXAMPLES(.*?)##/s";
        preg_match( $pattern, $long_desc, $matches );
        $code_snippets = [];
        if( count( $matches ) > 1 ) {
            $code_snippets[] = [
                'language' => 'bash',
                'code' => $matches[1]
            ];
        }

        $command_tags = [];

        $now = date( 'Y-m-d H:i:s' );
        $snippet_data = [
            'id' => $id,
            'snippet' => $code_snippets,
            'context' => '',
            'source' => 'wp-cli',
            'tags' => ['WordPress'],
            'command_tags' => $command_tags,
            'code_language_tags' => ['php'],
            'language' => 'en-US',
            'version' => $this->wp_cli_version,
            'url' => $path,
            'creator' => '',
            'parse_date' => $now,
            'code_creation_date' => '',
            'updated' => $now
        ];
        $snippet = new Snippet( ...$snippet_data );
        return $snippet;
    }
}
