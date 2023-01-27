<?php

namespace BnplPartners\Factoring004Diafan\Helper;

trait SessionTrait
{
    /**
     * @param string $key
     *
     * @return bool
     */
    protected function hasSession($key)
    {
        return array_key_exists('factoring004_' . $key, $_SESSION);
    }
    
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    protected function putSession($key, $value)
    {
        $_SESSION['factoring004_' . $key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getSession($key, $default = null)
    {
        return isset($_SESSION['factoring004_' . $key]) ? $_SESSION['factoring004_' . $key] : $default;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function removeSession($key)
    {
        unset($_SESSION['factoring004_' . $key]);
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    protected function pullSession($key, $default = null)
    {
        $value = $this->getSession($key, $default);

        $this->removeSession($key);

        return $value;
    }
}
