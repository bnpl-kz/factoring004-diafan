<?php

namespace BnplPartners\Factoring004Diafan\Helper;

use Dev;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Wa72\SimpleLogger\FileLogger;

class LoggerFactory
{
    const LOG_EXTENSION = '.log';

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $logFile;

    /**
     * @var bool
     */
    private $debug = MOD_DEVELOPER || MOD_DEVELOPER_ADMIN;

    public function __construct()
    {
        $this->path = ABSOLUTE_PATH . dirname(Dev::LOG_ERRORS_PATH) . '/';
        $this->logFile = $this->path . date('factoring004-Y-m-d') . static::LOG_EXTENSION;
    }

    /**
     * @return \BnplPartners\Factoring004Diafan\Helper\LoggerFactory
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param bool $debug
     *
     * @return \BnplPartners\Factoring004Diafan\Helper\LoggerFactory
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function createLogger()
    {
        if (!is_writable($this->path)) {
            return new NullLogger();
        }

        return new FileLogger($this->logFile, $this->debug ? LogLevel::DEBUG : LogLevel::INFO);
    }
}