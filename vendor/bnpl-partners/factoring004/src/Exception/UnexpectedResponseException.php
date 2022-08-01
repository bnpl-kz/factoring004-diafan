<?php

namespace BnplPartners\Factoring004\Exception;

use BnplPartners\Factoring004\Transport\ResponseInterface;
use Throwable;

class UnexpectedResponseException extends ApiException
{
    /**
     * @var \BnplPartners\Factoring004\Transport\ResponseInterface
     */
    private $response;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable $previous
     */
    public function __construct(
        ResponseInterface $response,
        $message = '',
        $code = 0,
        $previous = null
    ) {
        $message = (string) $message;
        $code = (int) $code;
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }

    /**
     * @return \BnplPartners\Factoring004\Transport\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
