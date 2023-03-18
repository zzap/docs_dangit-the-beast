<?php
/**
 * Writer interface
 * 
 * @package Docsdangit
 */
 
namespace Docsdangit\Interfaces;

interface Writer
{
    public function __construct();
    
    public function write();
}