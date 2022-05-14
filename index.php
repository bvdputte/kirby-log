<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bvdputte/kirbylog', [
    'options' => [
        'logname' => 'kirbylog.log',
        'defaultloglevel' => 'info',
        'exceptionlog' => true
    ],
    'siteMethods' => [
        'log' => function ($message, $loglevel = null, $context = []) {
            $logName = $name ?? kirby()->option("bvdputte.kirbylog.logname");
            $logger = new bvdputte\kirbyLog\Logger($logName);

            $logger->log($message, $loglevel, $context);
        },
        'logger' => function($name = null, $opts = []) {
            $logName = $name ?? kirby()->option("bvdputte.kirbylog.logname");
            $logger = new bvdputte\kirbyLog\Logger($logName, $opts);

            return $logger;
        }
    ],
    'hooks' => [
        'system.exception' => function ($exception) {
            if (kirby()->option("bvdputte.kirbylog.exceptionlog")) {
                $logger = new bvdputte\kirbyLog\Logger(kirby()->option("bvdputte.kirbylog.logname"));
                $logger->log($exception, 'error');
            }
        },
    ]
]);
