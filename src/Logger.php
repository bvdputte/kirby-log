<?php
namespace bvdputte\kirbyLog;
use Psr\Log\LogLevel;

class Logger {
    public $logger;

    protected $options = [];

    protected $logLevels = [
        "emergency",
        "alert",
        "critical",
        "error",
        "warning",
        "notice",
        "info",
        "debug"
    ];

    function __construct($name = null, array $options = [], $logLevelThreshold = LogLevel::DEBUG) {

        $logroot = kirby()->roots()->site() . DS . kirby()->option("bvdputte.kirbylog.logfolder");

        if(isset($name)) {
            $this->options["filename"] = $name;
        }

        $this->options = array_merge($this->options, $options);

        $this->logger = new \Katzgrau\KLogger\Logger(
            $logroot,
            $logLevelThreshold,
            $this->options
        );

        return $this->logger;
    }

    // public function getLogger() {
    //     return $this->logger;
    // }

    public function log($message, $level = "debug", $context = []) {
        $logger = $this->logger;

        //if (method_exists($logger, $level)) {
        if(array_search($level, $this->logLevels)) {
            $logger->$level($message, $context);
        } else {
            echo("Error: invalid loglevel code. Please use a PSR-3 loglevel code.");
        }
    }
}