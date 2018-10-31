<?php
/**
 * PHP 7 MAR
 * Scanner Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace alexia\mar;

class scanner
{
    /**
     * Project File or Path
     *
     * @var        string
     */
    private $projectPath = null;

    /**
     * List of files.
     *
     * @var        array
     */
    private $files = [];

    /**
     * List of file extension(s) to process.
     *
     * @var   array
     */
    private $extensions = ['php'];

    /**
     * @var array
     */
    private $excludedPaths = [];

    /**
     * Main Constructor
     *
     * @access    public
     * @param    string    Project file or folder
     * @param    array    [Optional] Array of allowed file extensions.
     * @throws \Exception
     * @return    void
     */
    public function __construct($projectPath, $extensions = null, $excludedPaths = array())
    {
        if (empty($projectPath)) {
            throw new \Exception(__METHOD__ . ": Project path given was empty.");
        }
        $this->projectPath = $projectPath;
        $this->excludedPaths = $excludedPaths;

        if (is_array($extensions)) {
            $this->setFileExtensions($extensions);
        }

        $this->recursiveScan($this->projectPath);
        reset($this->files);
    }

    /**
     * Sets the file extensions to be considered as PHP file. Ex:
     *
     *  array('php', 'inc')
     *
     * Do NOT include the dot before the extension
     *
     * @access    public
     * @param    array    Allowed file extensions
     */
    public function setFileExtensions(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Perform a recursive scan of the given path to return files only.
     *
     * @access    private
     * @param    string    Starting Folder
     * @return    void
     */
    private function recursiveScan($startFolder)
    {
        if (is_file($startFolder)) {
            $this->files[] = $startFolder;
            return;
        }
        $contents = scandir($startFolder);
        foreach ($contents as $content) {
            if (strpos($content, '.') === 0) {
                continue;
            }

            $path = $startFolder . DIRECTORY_SEPARATOR . $content;

            // If the path is to be excluded then move onto the next one
            foreach ($this->excludedPaths as $excludedPath) {
                if (substr($path, 0, strlen($excludedPath)) === $excludedPath) {
                    // Need continue 2 here as the loop we want to continue is 2 up
                    continue 2;
                }
            }

            if (is_dir($path)) {
                $this->recursiveScan($path);
            } else {
                $fileExtension = pathinfo($content, PATHINFO_EXTENSION);
                if (strlen($fileExtension) == 0 || !in_array($fileExtension, $this->getFileExtensions())) {
                    continue;
                }
                $this->files[] = $path;
            }
        }
    }

    /**
     * Gets the list of extensions to be considered PHP files when scanning
     *
     * @access    public
     * @return    array    File extensions to be considered as PHP files.
     */
    public function getFileExtensions()
    {
        return (array)$this->extensions;
    }

    /**
     * Scan the next file in the array and provide back an array of lines.
     *
     * @access    public
     * @return    mixed    Array of lines from the file or false for no more files.
     */
    public function scanNextFile()
    {
        $_file = each($this->files);
        if ($_file === false) {
            return false;
        }
        $file = $_file['value'];

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            $lines = [];
        }
        return $lines;
    }

    /**
     * Return the file path of the current array pointer.
     *
     * @access    public
     * @return    string    File Path
     */
    public function getCurrentFilePath()
    {
        return current($this->files);
    }

    /**
     * @return array
     */
    public function getExcludedPaths()
    {
        return $this->excludedPaths;
    }

    /**
     * @param array $excludedPaths
     */
    public function setExcludedPaths(array $excludedPaths)
    {
        $this->excludedPaths = $excludedPaths;
    }

}

?>
