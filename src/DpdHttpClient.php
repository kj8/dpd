<?php

declare(strict_types=1);

namespace Kj8\DPD;

use Kj8\DPD\Exception\DpdException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class DpdHttpClient
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly DpdConfig $config,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     *
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function post(string $uri, array $payload): array
    {
        $request = $this->requestFactory
            ->createRequest('POST', $this->config->baseUri.$uri)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json')
            ->withHeader('x-dpd-fid', (string) $this->config->fid)
            ->withHeader(
                'Authorization',
                'Basic '.base64_encode(
                    $this->config->login.':'.$this->config->password
                )
            );

        $request->getBody()->write(json_encode($payload, \JSON_THROW_ON_ERROR));

        $response = $this->httpClient->sendRequest($request);

        return $this->handleResponse($response);
    }

    /**
     * @param array<string, mixed> $query
     *
     * @return array<string, mixed>
     *
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function get(string $uri, array $query = []): array
    {
        $queryString = http_build_query($query);
        $url = $this->config->baseUri.$uri.'?'.$queryString;

        $request = $this->requestFactory
            ->createRequest('GET', $url)
            ->withHeader('Accept', 'application/json')
            ->withHeader('x-dpd-fid', (string) $this->config->fid)
            ->withHeader(
                'Authorization',
                'Basic '.base64_encode(
                    $this->config->login.':'.$this->config->password
                )
            );

        $response = $this->httpClient->sendRequest($request);

        return $this->handleResponse($response);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \JsonException
     */
    private function handleResponse(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();

        if ($response->getStatusCode() >= 400) {
            throw new DpdException(\sprintf('DPD API error (%d): %s', $response->getStatusCode(), $body));
        }

        return json_decode($body, true, 512, \JSON_THROW_ON_ERROR);
    }
}
