<?php

Kirby::plugin('bvdputte/kirbylog', [
    'options' => [
        'logfolder' => 'kirbylogs',
        'logname' => 'kirbylog.log',
        'defaultloglevel' => 'info'
    ],
]);

/*
    A little Kirby helper function
*/
if (! function_exists("kirbyLog")) {
    function kirbyLog($name = null, $opts = []) {
        $logName = $name ?? kirby()->option("bvdputte.kirbylog.logname");
        
        $kirbyLog = new bvdputte\kirbyLog\Logger($logName, $opts);
        
        return $kirbyLog;
    }
}