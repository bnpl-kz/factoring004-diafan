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

class Payment_bnpl_model extends Diafan {

    public function get($params, $pay)
    {
        print_r($pay);die;
    }
}
