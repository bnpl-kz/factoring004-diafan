<?php

if (! defined('DIAFAN'))
{
    $path = __FILE__;
    while(! file_exists($path.'/includes/404.php'))
    {
        $parent = dirname($path);
        if($parent == $path) exit;
        $path = $parent;
    }
    include $path.'/includes/404.php';
}

require_once __DIR__.'/vendor/autoload.php';

class Payment_bnpl_model extends Diafan {

    public function get($params, $pay)
    {
        $t = new \BnplPartners\Factoring004\Transport\Transport(
            new \GuzzleHttp\Psr7\HttpFactory(),
            new \GuzzleHttp\Psr7\HttpFactory(),
            new \GuzzleHttp\Psr7\HttpFactory(),
            new \GuzzleHttp\Client()
        );


        print_r($pay);die;
    }
}
