<?php

namespace Docsdangit\Backend\Service;

interface Repository
{
	public function store(Entity $entity): void;
}
