<?php

declare(strict_types=1);

namespace Docsdangit\Backend\Entity;

use Stringable;

class Tag implements Stringable
{
	public function __construct(
		public readonly string $tag
	) {}

	public function __toString(): string
	{
		return $this->tag;
	}
}
