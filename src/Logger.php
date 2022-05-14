<?php
namespace bvdputte\kirbyLog;

use Psr\Log\LogLevel;
use studio24\Rotate\Rotate;

class Logger
{
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

    public function __construct($name = null, array $options = [], $logLevelThreshold = LogLevel::DEBUG)
    {
        if (isset($name)) {
            $this->options["filename"] = $name;
        }

        $this->options = array_merge($this->options, $options);

        $this->logger = new \Katzgrau\KLogger\Logger(
            kirby()->roots()->logs(),
            $logLevelThreshold,
            $this->options
        );

        return $this->logger;
    }

    public function log($message, $loglevel = null, $context = [])
    {
        $logger = $this->logger;

        // Fallback to default loglevel if none passed
        $level = isset($loglevel) ? $loglevel : kirby()->option("bvdputte.kirbylog.defaultloglevel");

        if (array_search($level, $this->logLevels)) {
            $logger->$level($message, $context);
        } else {
            // Show error or fail silently
            if (kirby()->option("debug") == true) {
                throw new \Exception("Error: invalid loglevel code. Please use a PSR-3 loglevel code.");
            }
        }

        // Rotate logs
        if (kirby()->option("bvdputte.kirbylog.rotateLogs")) {
            $rotate = new Rotate($logger->getLogFilePath());
            $rotate->size(kirby()->option("bvdputte.kirbylog.rotateLogSizeThreshold"));
            $rotate->run();
        }
    }
}
