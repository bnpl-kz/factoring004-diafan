<?php

namespace BnplPartners\Factoring004\Exception;

use Throwable;

class AuthenticationException extends ApiException
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @param string $description
     * @param string $message
     * @param int $code
     * @param \Throwable $previous
     */
    public function __construct($description, $message = '', $code = 0, $previous = null)
    {
        $description = (string) $description;
        $message = (string) $message;
        $code = (int) $code;
        parent::__construct($message, $code, $previous);

        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
