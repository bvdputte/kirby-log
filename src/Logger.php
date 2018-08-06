<?php
namespace bvdputte\kirbyLog;
use Psr\Log\LogLevel;

class Logger {
    public $logfile;

    function __construct($name) {
        $logroot = kirby()->roots()->site() . DS . kirby()->option("bvdputte.kirbylog.logfolder");

        $this->logfile = new \Katzgrau\KLogger\Logger($logroot, LogLevel::INFO, array (
            'filename' => $name,
            'logFormat' => "[{date}] {message}"
        ));
    }

    public function write($message) {
        $logger = $this->logfile;

        $logger->info($message);
    }
}