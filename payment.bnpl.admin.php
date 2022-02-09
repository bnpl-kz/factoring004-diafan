<?php

if (! defined('DIAFAN'))
{
    $path = __FILE__; $i = 0;
    while(! file_exists($path.'/includes/404.php'))
    {
        if($i == 10) exit; $i++;
        $path = dirname($path);
    }
    include $path.'/includes/404.php';
}

class Payment_bnpl_admin
{
    public $config = array(
        "name" => 'Оплата в рассрочку',
        "params" => array()
    );
}
