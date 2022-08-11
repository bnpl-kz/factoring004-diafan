<?php

namespace BnplPartners\Factoring004Diafan\Helper;

use DB;
use Exception;

class Config
{
    /**
     * @var array<string, mixed>
     */
    private static $config = [];

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $items = static::all();

        return isset($items[$key]) ? $items[$key] : $default;
    }

    /**
     * @return array<string, mixed>
     */
    public static function all()
    {
        static::load();

        return static::$config;
    }

    /**
     * @return void
     */
    private static function load()
    {
        if (static::$config) {
            return;
        }

        $serialized = static::fetch();

        if ($serialized) {
            static::$config = unserialize($serialized);
        }
    }

    /**
     * @return string|null
     */
    private static function fetch()
    {
        try {
            $result = DB::query_fetch_value(
                "SELECT params FROM {payment} WHERE payment = 'factoring004' ORDER BY id DESC LIMIT 1",
                'params'
            );
        } catch (Exception $e) {
            return null;
        }

        return $result ? $result[0] : null;
    }
}
