<?php

if (!defined('DIAFAN')) {
    $path = __FILE__;

    while (!file_exists($path . '/includes/404.php')) {
        $parent = dirname($path);
        if ($parent == $path) {
            exit;
        }
        $path = $parent;
    }

    include $path . '/includes/404.php';
}

class Order_factoring004_admin_order extends Diafan
{

}
