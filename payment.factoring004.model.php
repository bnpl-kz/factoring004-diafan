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

class Payment_factoring004_model extends Diafan {

    public function get($params, $pay)
    {

    }
}
