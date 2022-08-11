<?php

if (! defined('DIAFAN'))
{
    include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/includes/404.php';
}

require_once 'Factoring004.php';

class Payment_factoring004_model extends Diafan
{
    public function get($params, $pay)
    {
        try {
            $factoring004 = new Factoring004($params['factoring004_api_host'], $params['factoring004_oauth_token']);
            $response = $factoring004->createPreapp(array_merge($params, $pay));
            return $this->diafan->redirect($response);
        } catch (\BnplPartners\Factoring004\Exception\ValidationException $e) {
            return $e->getResponse()->toArray();
        }
    }
}
