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

require_once 'vendor/autoload.php';

use BnplPartners\Factoring004Diafan\Helper\Config as Factoring004Config;

$this->diafan->_site->css_view[] = 'modules/payment/backend/factoring004/assets/css/factoring004-paymentschedule.css';
$this->diafan->_site->js_view[] = 'modules/payment/backend/factoring004/assets/js/factoring004-paymentschedule.js';
$this->diafan->_site->js_view[] = 'modules/payment/backend/factoring004/assets/js/front.js';

$offer_file = '';

$offer_filename = Factoring004Config::get('factoring004_offer_file');

if ($offer_filename) $offer_file = '/'.USERFILES . '/' . $offer_filename;

echo "<script>
    window.factoring004_offer_file = '$offer_file';
</script>";