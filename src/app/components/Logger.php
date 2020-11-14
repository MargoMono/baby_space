<?php

namespace App\Log;

use Monolog\Handler\FirePHPHandler;
use Monolog\Logger as LoggerBase;
use Monolog\Handler\StreamHandler;

class Logger
{
    public static function getLogger(string $appName = null)
    {
        $logger = new LoggerBase($appName);
        $logger->pushHandler(new StreamHandler('logs/' . date('Y_m_d') . '.log'));
        $logger->pushHandler(new FirePHPHandler());

        return $logger;
    }
}