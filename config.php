<?php

Kirby::plugin('bvdputte/kirbylog', [
    'options' => [
        'logfolder' => 'kirbylogs',
        'logname' => 'kirbylog.log',
        'logformat' => "[{date}] {message}"
    ],
]);

if (! function_exists("kirbyLog")) {
    function kirbyLog($name = null, $opts = []) {
        $logName = $name ?? kirby()->option("bvdputte.kirbylog.logname");
        $options = array (
            'logFormat' => kirby()->option("bvdputte.kirbylog.logformat"),
        );
        $options = array_merge($options, $opts);
        
        $kirbyLog = new bvdputte\kirbyLog\KirbyLog($logName, $options);
        
        return $kirbyLog;
    }
}