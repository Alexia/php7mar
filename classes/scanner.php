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
	 * Main Constructor
	 *
	 * @access	public
	 * @param	string	Project file or folder
	 * @return	void
	 */
	public function __construct($projectPath) {
		if (empty($projectPath)) {
			throw new Exception(__METHOD__.": Project path given was empty.");
		}
		$this->projectPath = $projectPath;
		$this->recursiveScan($this->projectPath);
	}

	/**
	 * Perform a recursive scan of the given path to return files only.
	 *
	 * @access	private
	 * @param	string	Starting Folder
	 * @return	void
	 */
	private function recursiveScan($startFolder) {
		$contents = scandir($startFolder);
		foreach ($contents as $content) {
			if ($content == '.' || $content == '..') {
				continue;
			}
			var_dump($content);
			$path = $startFolder.$content;
			if (is_dir($path)) {
				$this->recursiveScan($path.DIRECTORY_SEPARATOR);
			} else {
				if (strpos($content, '.php') === false) {
					continue;
				}
				$this->files[] = $path;
			}
		}
	}
}
?>