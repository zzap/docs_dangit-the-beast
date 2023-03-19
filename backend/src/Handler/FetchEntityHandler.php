<?php

declare(strict_types=1);

namespace Docsdangit\Backend\Handler;

use Docsdangit\Backend\Serialize\DocsEntryToArray;
use Docsdangit\Backend\Service\Repository;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FetchEntityHandler implements RequestHandlerInterface
{
	public function __construct(
		private readonly Repository $repository,
		private readonly DocsEntryToArray $serializer
	) {}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$query = $request->getQueryParams();

		$search = null;
		if (isset($query['search'])) {
			$search = $query['search'];
		}

		$results = $this->repository->fetch($search, 15, 0);

		$response = [];
		foreach ($results as $result) {
			$response[] = $this->serializer->serialize($result);
		}

		return new JsonResponse($response);
	}
}
