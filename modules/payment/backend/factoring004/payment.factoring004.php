<?php

require_once 'vendor/autoload.php';

use BnplPartners\Factoring004Diafan\Handler\FileHandler;
use BnplPartners\Factoring004Diafan\Handler\PostLinkHandler;

$route = $_GET['rewrite'];

if ($route === 'factoring004/file-handler/upload') {
    $fileHandler = new FileHandler();
    $fileHandler->upload($_FILES['file']);
    return;
} else if ($route === 'factoring004/file-handler/destroy') {
    $fileHandler = new FileHandler();
    $fileHandler->destroy($_POST['filename']);
} else if ($route === 'factoring004/post-link') {
    $postLink = new PostLinkHandler();
    $postLink($this->diafan->_payment);
    return;
} else {
    Custom::inc('includes/404.php');
    return;
}

