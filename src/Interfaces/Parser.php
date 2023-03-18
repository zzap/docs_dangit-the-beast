<?php
/**
 * Parser interface
 */

 namespace Docsdangit\Interfaces;

 interface Parser
 {
     public function parse($url);

     public function reset();
 }
