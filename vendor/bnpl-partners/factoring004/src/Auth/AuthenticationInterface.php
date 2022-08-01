<?php

namespace BnplPartners\Factoring004\Auth;

use Psr\Http\Message\RequestInterface;

interface AuthenticationInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\RequestInterface
     */
    public function apply($request);
}
