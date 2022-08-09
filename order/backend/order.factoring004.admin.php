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

class Order_factoring004_admin extends Diafan
{
    /**
     * @var array<string, mixed>
     */
    public $config = [
        'name' => 'Рассрочка 0-0-4',
        'params' => [],
    ];
}
