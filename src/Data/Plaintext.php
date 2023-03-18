<?php
/**
 * Plaintext writer
 * 
 * @package Docsdangit
 */
namespace Docsdangit\Data;

use Docsdangit\Interfaces\Writer;

class Plaintext implements Writer {
    protected Snippet $snippet;

    protected string $filename;

    public function __construct( Snippet $snippet, string $filename ) {
        $this->snippet = $snippet;
    }

    public function write() {
        $file = fopen( $this->filename, 'a');
    }
}