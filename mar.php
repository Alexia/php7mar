<?php
/**
 * PHP 7 MAR
 * MAR Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar;

class main {
	/**
	 * Project File or Path
	 *
	 * @var		string
	 */
	private $projectPath = null;

	/**
	 * Options class
	 *
	 * @var		object
	 */
	private $options = null;

	/**
	 * Reporter class
	 *
	 * @var		object
	 */
	private $reporter = null;

	/**
	 * Tests class
	 *
	 * @var		object
	 */
	private $tests = null;

	/**
	 * Scanner class
	 *
	 * @var		object
	 */
	private $scanner = null;

	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		define('PHP7MAR_DIR', __DIR__);
		spl_autoload_register([self, 'autoloader'], true, false);

		//Setup command line options/switches.
		$this->options = new options();

		$projectPath = self::getRealPath($this->options->getOption('f'));
		if ($projectPath !== false) {
			$this->projectPath = $projectPath;
		} else {
			die("The project path given could not be found.\n");
		}

		//Initialize the reporter class.(File output)
		$this->reporter = new reporter($this->projectPath, $this->options->getOption('r'));

		$this->tests = new tests();

		$start = microtime(true);
		$this->scanner = new scanner($this->projectPath);

		$this->run();
		$end = microtime(true);
		$runTime = $end - $start;
		$this->reporter->add("Processing took ".$runTime, 0, 1);

		//$this->reporter->addSections();
	}

	/**
	 * Function Documentation
	 *
	 * @access	private
	 * @return	void
	 */
	private function run() {
		while ($lines = $this->scanner->scanNextFile()) {
			$totalFiles++;
			foreach ($lines as $index => $line) {
				$totalLines++;
				//$result = $this->tests->processLine($line);
			}
		}
		$this->reporter->add("Processed {$totalLines} lines contained in {$totalFiles} files.", 0, 1);
	}

	/**
	 * Autoloader
	 *
	 * @access	public
	 * @param	string	Class name to load automatically.
	 * @return	void
	 */
	static public function autoloader($classname) {
		$file = PHP7MAR_DIR.DIRECTORY_SEPARATOR.'classes'.str_replace('\\', DIRECTORY_SEPARATOR, str_replace('mar', '', $classname)).'.php';
		if (is_file($file)) {
			require_once($file);
		} else {
			throw new \Exception(__CLASS__.": Class file for {$classname} not found at {$file}.");
		}
	}

	/**
	 * Get a full real path name to a given path.
	 *
	 * @access	public
	 * @param	string	File/Folder Path
	 * @return	mixed	File/Folder path or false on error.
	 */
	static public function getRealPath($path) {
		if (strpos($path, '~') === 0) {
			$path = substr_replace($path, $_SERVER['HOME'], 0, 1);
		}

		$_path = realpath($path);
		if (!empty($path) && $_path !== false) {
			return rtrim($_path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		}
		return false;
	}
}
$mar = new \mar\main();
?>