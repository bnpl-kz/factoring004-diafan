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
    public $config;

    public function __construct()
    {
        $this->config = array(
            "name" => 'Оплата в рассрочку',
            "params" => array(
                'bnpl_consumer_key' => 'Consumer Key',
                'bnpl_consumer_secret' => 'Consumer Secret',
                'bnpl_api_host' => 'API Host',
                'bnpl_partner_name' => 'Partner Name',
                'bnpl_partner_code'=>'Partner Code',
                'bnpl_point_code'=>'Point Code',
                'bnpl_success_redirect_url'=>'Success Redirect URL',
                'bnpl_fail_redirect_url'=>'Fail Redirect URL',
                'bnpl_post_link'=>'Post link'
            )
        );
    }
}
