<?php
/**
 * Parser interface
 */

namespace Docsdangit\Parsers;

interface ParserInterface
{
    public function parse(array $data): array;
}
