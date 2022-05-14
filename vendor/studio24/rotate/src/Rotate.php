<?php
namespace studio24\Rotate;

/**
 * Class to manage file rotation
 *
 * @package studio24\Rotate
 */
class Rotate extends RotateAbstract
{
    /**
     * Number of copies to keep, defaults to 10
     *
     * @var int
     */
    protected $keepNumber = 10;

    /**
     * Filesize to rotate files on (in bytes)
     *
     * @var int
     */
    protected $sizeToRotate;

    /**
     * Set the number of old copies to keep
     *
     * @param $number
     */
    public function keep($number)
    {
        $this->keepNumber = $number;
    }

    /**
     * Return number of old copies to keep
     *
     * @return int
     */
    public function getKeepNumber()
    {
        return $this->keepNumber;
    }

    /**
     * Set the filesize to rotate files on
     *
     * @param string $size Define as an number with a string suffix indicating the unit measurement, e.g. 5MB
     * @throws RotateException
     */
    public function size($size)
    {
        if (!preg_match('/^(\d+)\s?(B|KB|MB|GB)$/i', $size, $m)) {
            throw new RotateException("You must define size in the format 10B|KB|MB|GB");
        }
        if ($m[1] === 0) {
            throw new RotateException("You must define a non-zero size to rotate files on");
        }

        switch (strtoupper($m[2])) {
            case 'B':
                $this->sizeToRotate = $m[1];
                break;
            case 'KB':
                $this->sizeToRotate = $m[1] * 1024;
                break;
            case 'MB':
                $this->sizeToRotate = $m[1] * 1024 * 1024;
                break;
            case 'GB':
                $this->sizeToRotate = $m[1] * 1024 * 1024 * 1024;
                break;
        }
    }

    /**
     * Return filesize to rotate files on (in bytes)
     *
     * @return int
     */
    public function getSizeToRotate()
    {
        return $this->sizeToRotate;
    }

    /**
     * Have we defined a filesize to rotate on?
     *
     * @return bool
     */
    public function hasSizeToRotate()
    {
        return (is_int($this->getSizeToRotate()) && $this->getSizeToRotate() !== 0);
    }

    /**
     * Run the file rotation
     *
     * @return array Array of files which have been rotated
     * @throws FilenameFormatException
     * @throws RotateException
     */
    public function run()
    {
        if (!$this->hasFilenameFormat()) {
            throw new FilenameFormatException('You must set a filename format to match files against');
        }

        $rotated = [];

        $dir = new DirectoryIterator($this->getFilenameFormat()->getPath());
        $dir->setFilenameFormat($this->getFilenameFormat());
        foreach ($dir as $file) {
            if ($file->isFile() && $file->isMatch()) {

                // Skip if rotate size specified and initial matched file doesn't exceed this
                if ($this->hasSizeToRotate()) {
                    if ($file->getSize() < $this->getSizeToRotate()) {
                        continue;
                    }
                }

                // Rotate files
                for ($x = $this->keepNumber; $x--; $x > 0) {
                    $fileToRotate = $file->getPath() . '/' . $file->getRotatedFilename($x);
                    if (!file_exists($fileToRotate)) {
                        continue;
                    }

                    if ($x === $this->keepNumber) {
                        if (!$this->isDryRun()) {
                            if (!unlink($fileToRotate)) {
                                throw new RotateException('Cannot delete file: ' . $file->getRotatedFilename($x));
                            }
                        }
                        $rotated[] = $fileToRotate;

                    } else {
                        if (!$this->isDryRun()) {
                            if (!rename($fileToRotate, $file->getPath() . '/' . $file->getRotatedFilename($x + 1))) {
                                throw new RotateException('Cannot rotate file: ' . $file->getRotatedFilename($x));
                            }
                        }
                        $rotated[] = $fileToRotate;
                    }
                }

                if (!$this->isDryRun()) {
                    if (!rename($file->getPath() . '/' . $file->getBasename(), $file->getPath() . '/' . $file->getRotatedFilename(1))) {
                        throw new RotateException('Cannot rotate file: ' . $file->getBasename());
                    }
                }
                $rotated[] = $file->getPath() . '/' . $file->getBasename();
            }
        }

        return $rotated;
    }

}