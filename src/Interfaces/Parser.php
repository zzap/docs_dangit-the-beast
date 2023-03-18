<?php
/**
 * Parser interface
 */

 namespace Docsdangit\Command;

 interface Parser
 {
     public function parse($url);

     public function reset();
 }