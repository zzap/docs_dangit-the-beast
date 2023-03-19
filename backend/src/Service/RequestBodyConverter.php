<?php

namespace Docsdangit\Backend\Service;

use Psr\Http\Message\ServerRequestInterface;

interface RequestBodyConverter
{
	public function convert(ServerRequestInterface $request): Entity;
}
