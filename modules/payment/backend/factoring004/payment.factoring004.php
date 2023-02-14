<?php

require_once 'vendor/autoload.php';

use BnplPartners\Factoring004Diafan\Handler\FileHandler;
use BnplPartners\Factoring004Diafan\Handler\PostLinkHandler;

$route = $_GET['rewrite'];

if ($route === 'factoring004/post-link') {
    $postLink = new PostLinkHandler();
    $postLink($this->diafan->_payment);
    return;
} else {
    Custom::inc('includes/404.php');
    return;
}

