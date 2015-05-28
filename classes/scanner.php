<?php
/**
 * PHP 7 MAR
 * Scanner Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar;

class scanner {
	/**
	 * Project File or Path
	 *
	 * @var		string
	 */
	private $projectPath = null;

	/**
	 * List of files.
	 *
	 * @var		array
	 */
	private $files = [];

  /**
   * List of file extension to process.
   *
   * @var   array
   */
  private $extensions = ['php'];

	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @param	string	Project file or folder
	 * @return	void
	 */
	public function __construct($projectPath, Array $extensions = null) {
		if (empty($projectPath)) {
			throw new \Exception(__METHOD__.": Project path given was empty.");
		}
		$this->projectPath = $projectPath;

    if (!is_null($extensions)) {
      $this->extensions = $extensions;
    }

		$this->recursiveScan($this->projectPath);
		reset($this->files);
	}

	/**
	 * Perform a recursive scan of the given path to return files only.
	 *
	 * @access	private
	 * @param	string	Starting Folder
	 * @return	void
	 */
	private function recursiveScan($startFolder) {
		if (is_file($startFolder)) {
			$this->files[] = $startFolder;
			return;
		}
		$contents = scandir($startFolder);
		foreach ($contents as $content) {
			if (strpos($content, '.') === 0) {
				continue;
			}

			$path = $startFolder.DIRECTORY_SEPARATOR.$content;
			if (is_dir($path)) {
				$this->recursiveScan($path);
			} else {
        $fileExtension = pathinfo($content, PATHINFO_EXTENSION);
				if (strlen($fileExtension) == 0 || !in_array($fileExtension, $this->extensions)) {
					continue;
				}
				$this->files[] = $path;
			}
		}
	}

	/**
	 * Scan the next file in the array and provide back an array of lines.
	 *
	 * @access	public
	 * @return	mixed	Array of lines from the file or false for no more files.
	 */
	public function scanNextFile() {
		$_file = each($this->files);
		if ($_file === false) {
			return false;
		}
		$file = $_file['value'];

		$lines = file($file);
		if ($lines === false) {
			return false;
		}
		return $lines;
	}

	/**
	 * Return the file path of the current array pointer.
	 *
	 * @access	public
	 * @return	string	File Path
	 */
	public function getCurrentFilePath() {
		return current($this->files);
	}

  /**
   * Sets the file extensions to be considered as PHP file. Ex:
   *
   *  array('php', 'inc')
   *
   * Do NOT include the dot before the extension
   *
   * @access public
   * @param array $extensions
   */
  public function setFileExtensions(Array $extensions) {
    $this->extensions = $extensions;
  }

  /**
   * Gets the list of extensions to be considered PHP files when scanning
   *
   * @return array File extensions to be considered as PHP files
   */
  public function getFileExtensions() {
    return $this->extensions;
  }
}
?>