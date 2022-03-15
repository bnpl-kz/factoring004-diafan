<?php

if (! defined('DIAFAN')) {
    $path = __FILE__;
    while(! file_exists($path.'/includes/404.php'))
    {
        $parent = dirname($path);
        if($parent == $path) exit;
        $path = $parent;
    }
    include $path.'/includes/404.php';
}

if (empty($_REQUEST["status"]) || !is_string($_REQUEST["status"])) {
    Custom::inc('includes/404.php');
    return;
}

if (empty($_REQUEST["billNumber"]) || !is_string($_REQUEST["billNumber"])) {
    Custom::inc('includes/404.php');
    return;
}

if (empty($_REQUEST["preappId"]) || !is_string($_REQUEST["preappId"])) {
    Custom::inc('includes/404.php');
    return;
}

if ($_REQUEST["status"] === 'preapproved' || $_REQUEST["status"] === 'declined') {
    header('Content-Type: application/json');
    echo json_encode(['status' => $_REQUEST["status"]]);
    return;
}

if ($_REQUEST["status"] !== 'completed') {
    Custom::inc('includes/404.php');
    return;
}

$pay = $this->diafan->_payment->check_pay($_REQUEST["billNumber"], 'bnpl');

$this->diafan->_payment->success($pay, 'pay');

header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);
