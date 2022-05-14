<?php
namespace studio24\Rotate;

// @todo support '/cygdrive/z/bakerdays-shared/' . $environment . '/processed-preview/*/preview_*.jpg'

class FilenameFormat
{

    /**
     * Path to files to rotate / delete
     *
     * @var string
     */
    protected $path;

    /**
     * Filename pattern of files to rotate / delete
     *
     * @var string
     */
    protected $filenamePattern;

    /**
     * Filename regex pattern to match files
     *
     * @var string
     */
    protected $filenameRegex;

    /**
     * Date format within filename
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * Constructor
     *
     * @param string $filenameFormat Filename format to match files against
     * @throws FilenameFormatException
     */
    public function __construct($filenameFormat)
    {
        $this->path = dirname($filenameFormat);
        if (!is_dir($this->path) || !is_readable($this->path)) {
            throw new FilenameFormatException("Directory path does not exist or is not readable at: " . strip_tags($filenameFormat));
        }

        $this->filenamePattern = basename($filenameFormat);
        $this->filenameRegex = $this->extractRegex($this->filenamePattern);
    }

    /**
     * Extract regex pattern from filename pattern
     *
     * * matches any string, for example *.log matches all files ending .log
     * {Ymd} = matches time segment in a file, for example order.{Ymd}.log matches a file in the format order.20160401.log
     * Any date format supported by DateTime::createFromFormat is allowed (excluding the Timezone identifier 'e' and whitespace and separator characters)
     *
     * @param string $filename
     * @return string Regex pattern for matching files (with the ungreedy modifier set)
     * @throws FilenameFormatException
     */
    public function extractRegex($filename)
    {
        if (strpos('/', $filename) !== false) {
            throw new FilenameFormatException("Filename part cannot contain '/' character");
        }

        $escape = [
            '/\./'     => '\.',
            '/\+/'     => '\+',
            '/\:/'     => '\:',
            '/\-/'     => '\-'
        ];
        $pattern = preg_replace(array_keys($escape), array_values($escape), $filename);

        $replacements = [
            '/\*/'     => '.+'
        ];
        $pattern = preg_replace(array_keys($replacements), array_values($replacements), $pattern);

        if (preg_match('/{([^}]+)}/', $filename, $m)) {
            $dateFormat = $m[1];
            $validDateFormat = 'djDlSzFMmnYyaAghGHisuOPTU';
            if (!empty(array_diff(str_split($dateFormat), str_split($validDateFormat)))) {
                throw new FilenameFormatException("Date format is not valid: $dateFormat");
            }

            $this->dateFormat = $dateFormat;
            $pattern = preg_replace('/{[^}]+}/', '([^.]+)', $pattern);
        }

        return '/^' . $pattern . '$/';
    }

    /**
     * Return path to folder we're looking for files in
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return filename pattern to match files
     *
     * @return string
     */
    public function getFilenamePattern()
    {
        return $this->filenamePattern;
    }

    /**
     * Return regex to match files
     *
     * @return string
     */
    public function getFilenameRegex()
    {
        return $this->filenameRegex;
    }

    /**
     * Does the filename pattern contain a date format?
     *
     * @return bool
     */
    public function hasDateFormat()
    {
        return ($this->dateFormat !== null);
    }

    /**
     * Return the date format
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

}