<?php

declare(strict_types=1);

namespace Docsdangit\Backend\Handler;

use Docsdangit\Backend\Service\Repository;
use Docsdangit\Backend\Service\RequestBodyConverter;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PostEntityHandler implements RequestHandlerInterface
{
	public function __construct(
		private RequestBodyConverter $requestBodyConverter,
		private Repository $repository,
	) {}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$entity = $this->requestBodyConverter->convert($request);

		$this->repository->store($entity);

		return new RedirectResponse('/', 201);
	}
}
