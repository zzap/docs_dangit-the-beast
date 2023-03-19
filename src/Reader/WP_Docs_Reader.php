<?php declare(strict_types=1);

namespace Docsdangit\Reader;

use http\Exception\BadUrlException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class WP_Docs_Reader implements ReaderInterface
{

    private const WP_DOCS_URL = 'https://developer.wordpress.org/wp-json/wp/v2/comments';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    )
    {
    }

    public function read(): array
    {
        $response = $this->httpClient->request('GET', self::WP_DOCS_URL);
        $headers = $response->getHeaders();

        $totalPages = array_pop($headers['x-wp-total']);
        if (!$totalPages) {
            throw new BadUrlException('No or empty x-wp-total header found');
        }
        $offset = 0;
        $data = [];
        while ($offset <= $totalPages) {
            $pageResponse = $this->httpClient->request('GET', self::WP_DOCS_URL);
            $items = json_decode($pageResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
            foreach ($items as $item) {
                $data[] = $item;
            }

            $offset += 100;
        }

        return $data;
    }
}
