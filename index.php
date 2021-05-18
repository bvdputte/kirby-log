<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bvdputte/kirbylog', [
    'options' => [
        'logname' => 'kirbylog.log',
        'defaultloglevel' => 'info'
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
    ]
]);
