<?php

namespace BnplPartners\Factoring004\Transport;

use BnplPartners\Factoring004\Auth\AuthenticationInterface;
use BnplPartners\Factoring004\Auth\NoAuth;
use BnplPartners\Factoring004\Exception\DataSerializationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

abstract class AbstractTransport implements TransportInterface
{
    use LoggerAwareTrait;

    const METHODS_WITHOUT_BODY = ['GET', 'HEAD', 'OPTIONS', 'DELETE'];
    const DEFAULT_CONTENT_TYPE = 'application/json';
    const LOGGER_PREFIX = 'bnpl-partners/factoring004';

    /**
     * @var array<string, string>
     */
    protected $headers = [];
    /**
     * @var \Psr\Http\Message\UriInterface|null
     */
    protected $baseUri;
    /**
     * @var \BnplPartners\Factoring004\Auth\AuthenticationInterface
     */
    protected $authentication;

    public function __construct()
    {
        $this->authentication = new NoAuth();
        $this->setLogger(new NullLogger());
    }

    /**
     * @param string $uri
     * @return \BnplPartners\Factoring004\Transport\TransportInterface
     */
    public function setBaseUri($uri)
    {
        $this->baseUri = $this->createUri($uri);
        return $this;
    }

    /**
     * @param mixed[] $headers
     * @return \BnplPartners\Factoring004\Transport\TransportInterface
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param \BnplPartners\Factoring004\Auth\AuthenticationInterface $authentication
     * @return \BnplPartners\Factoring004\Transport\TransportInterface
     */
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
        return $this;
    }

    /**
     * @param string $path
     * @param mixed[] $query
     * @param mixed[] $headers
     * @return \BnplPartners\Factoring004\Transport\ResponseInterface
     */
    public function get($path, $query = [], $headers = [])
    {
        return $this->request(__FUNCTION__, $path, $query, $headers);
    }

    /**
     * @param string $path
     * @param mixed[] $data
     * @param mixed[] $headers
     * @return \BnplPartners\Factoring004\Transport\ResponseInterface
     */
    public function post($path, $data = [], $headers = [])
    {
        return $this->request(__FUNCTION__, $path, $data, $headers);
    }

    /**
     * @param string $method
     * @param string $path
     * @param mixed[] $data
     * @param mixed[] $headers
     * @return \BnplPartners\Factoring004\Transport\ResponseInterface
     */
    public function request($method, $path, $data = [], $headers = [])
    {
        $request = $this->prepareRequest(strtoupper($method), $path, $data, $headers);

        /** @psalm-suppress PossiblyNullReference */
        $this->logger->debug(
            static::LOGGER_PREFIX . ': Request: ' .
            sprintf(
                '%s %s %s',
                $request->getMethod(),
                (string) $request->getUri(),
                (string) $request->getBody()
            )
        );

        $response = $this->sendRequest($request);

        $this->logger->debug(
            static::LOGGER_PREFIX . ': Response: '.
            sprintf(
                '%d %s %s',
                $response->getStatusCode(),
                (string) $request->getUri(),
                (string) $response->getBody()
            )
        );

        return $this->convertResponse($response);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @param string $method
     * @param string $path
     * @param mixed[] $data
     * @param mixed[] $headers
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function prepareRequest($method, $path, $data, $headers)
    {
        $isWithoutBody = in_array($method, static::METHODS_WITHOUT_BODY, true);
        $query = $isWithoutBody ? $data : [];

        $request = $this->createRequest($method, $this->prepareUri($path, $query));
        $request = $this->mergeRequestHeaders($request, $headers);

        if (!$isWithoutBody) {
            $stream = $this->createStream($this->serializeData($data, $request));
            $request = $request->withBody($stream);
        }

        return $this->authentication->apply($request);
    }

    /**
     * @param array<string, mixed> $query
     * @param string $path
     * @return \Psr\Http\Message\UriInterface
     */
    protected function prepareUri($path, $query)
    {
        $uri = isset($this->baseUri) ? $this->baseUri : $this->createUri('/');

        $path = rtrim($uri->getPath(), '/') . '/' . ltrim($path, '/');
        $uri = $uri->withPath($path);

        return $query ? $uri->withQuery(http_build_query($query)) : $uri;
    }

    /**
     * @param string $method
     * @param \Psr\Http\Message\UriInterface $uri
     * @return \Psr\Http\Message\RequestInterface
     */
    abstract protected function createRequest($method, $uri);

    /**
     * @param string $content
     * @return \Psr\Http\Message\StreamInterface
     */
    abstract protected function createStream($content);

    /**
     * @param string $uri
     * @return \Psr\Http\Message\UriInterface
     */
    abstract protected function createUri($uri);

    /**
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     * @param \Psr\Http\Message\RequestInterface $request
     * @return PsrResponseInterface
     */
    abstract protected function sendRequest($request);

    /**
     * @param array<string, string> $headers
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function mergeRequestHeaders($request, $headers)
    {
        $headers = array_merge($this->headers, $headers);

        foreach ($headers as $name => $header) {
            $request = $request->withHeader($name, $header);
        }

        return $request;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @param \Psr\Http\Message\RequestInterface $request
     * @return string
     */
    protected function serializeData($data, $request)
    {
        if (!$data) {
            return '';
        }

        $contentType = $request->getHeaderLine('Content-Type') ?: static::DEFAULT_CONTENT_TYPE;

        if (strpos($contentType, 'json') !== false) {
            $json = json_encode($data);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }

            throw new DataSerializationException('Invalid JSON format');
        }

        if ($contentType === 'application/x-www-form-urlencoded') {
            return http_build_query($data);
        }

        throw new DataSerializationException('Unsupported content type ' . $contentType);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @param PsrResponseInterface $response
     * @return \BnplPartners\Factoring004\Transport\ResponseInterface
     */
    protected function convertResponse($response)
    {
        return Response::createFromPsrResponse($response);
    }
}
