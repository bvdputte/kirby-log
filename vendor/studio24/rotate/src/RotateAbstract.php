<?php
namespace studio24\Rotate;

abstract class RotateAbstract
{
    /**
     * The filename format we're matching against
     *
     * @var FilenameFormat
     */
    protected $filenameFormat;

    /**
     * Do we run this as a dry-run, i.e. not actually delete any files?
     *
     * @var boolean
     */
    protected $dryRun = false;

    /**
     * Constructor
     *
     * @param string|null $filenameFormat
     */
    public function __construct ($filenameFormat = null)
    {
        if ($filenameFormat !== null) {
            $this->setFilenameFormat($filenameFormat);
        }
    }

    /**
     * Set the filename format we're matching
     *
     * @param string $filenameFormat Filename format to match files against
     */
    public function setFilenameFormat($filenameFormat)
    {
        $this->filenameFormat = new FilenameFormat($filenameFormat);
    }

    /**
     * Return the filename format we're matching
     *
     * @return FilenameFormat
     */
    public function getFilenameFormat()
    {
        return $this->filenameFormat;
    }

    /**
     * Does this object have a valid filename format set?
     *
     * @return bool
     */
    public function hasFilenameFormat()
    {
        return ($this->filenameFormat instanceof FilenameFormat);
    }

    /**
     * Set the dry run flag, which means we don't actually delete any files
     *
     * @param $dryRun
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun  = (bool) $dryRun;
    }

    /**
     * Are we in dry-run mode?
     *
     * @return bool
     */
    public function isDryRun()
    {
        return $this->dryRun;
    }


}
