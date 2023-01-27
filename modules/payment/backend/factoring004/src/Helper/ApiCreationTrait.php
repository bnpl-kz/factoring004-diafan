<?php

namespace BnplPartners\Factoring004Diafan\Helper;

use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;

trait ApiCreationTrait
{
    /**
     * @return \BnplPartners\Factoring004\Api
     */
    protected function createApi()
    {
        return Api::create(
            Config::get('factoring004_api_host'),
            new BearerTokenAuth(Config::get('factoring004_as_token')),
            $this->transport
        );
    }

    /**
     * @return string
     */
    protected function getMerchantId()
    {
        return Config::get('factoring004_partner_code');
    }
}
