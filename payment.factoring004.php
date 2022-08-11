<?php

$route = $_GET['rewrite'];

if ($route === 'factoring004/file-handler/upload') {
    require_once 'FileHandler.php';
    $fileHandler = new FileHandler();
    $fileHandler->upload($_FILES['file']);
} else if ($route === 'factoring004/file-handler/destroy') {
    require_once 'FileHandler.php';
    $fileHandler = new FileHandler();
    $fileHandler->destroy($_POST['filename']);
} else if ($route === 'factoring004/post-link') {
    require_once 'PostLink.php';
    $postLink = new PostLink();
    $postLink($this->diafan->_payment);
} else {
    Custom::inc('includes/404.php');
    return;
}

