<?php

namespace BnplPartners\Factoring004\Auth;

use Psr\Http\Message\RequestInterface;

class BasicAuth implements AuthenticationInterface
{
    const HEADER_NAME = 'Authorization';
    const AUTH_SCHEMA = 'Basic';

    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $username = (string) $username;
        $password = (string) $password;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\RequestInterface
     */
    public function apply($request)
    {
        return $request->withHeader(static::HEADER_NAME, static::AUTH_SCHEMA . ' ' . $this->encodeCredentials());
    }

    /**
     * @return string
     */
    private function encodeCredentials()
    {
        return base64_encode($this->username . ':' . $this->password);
    }
}
