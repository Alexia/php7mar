<?php
/**
 * PHP 7 MAR
 * MAR Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace Alexia\Mar;

use Alexia\Mar\Option\options;
use Alexia\Mar\Reporter\reporter;
use Alexia\Mar\Scanner\scanner;
use Alexia\Mar\Tester\tests;

class mar {
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
		define('PHP7MAR_VERSION', '0.0.1');

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

		$this->tests = new tests($this->options->getOption('t'));

		if (!empty($this->options->getOption('php'))) {
			$this->tests->setPHPBinaryPath($this->options->getOption('php'));
		}

		$start = microtime(true);
		$this->scanner = new scanner($this->projectPath);

		$this->run();
		$end = microtime(true);
		$runTime = $end - $start;
		$this->reporter->add("Processing took {$runTime} seconds.", 0, 1);

		$this->reporter->addSections();

		echo "Report located at: ".$this->reporter->getReportFilePath()."\n";
	}

	/**
	 * Run tests, generator report sections.
	 *
	 * @access	private
	 * @return	void
	 */
	private function run() {
		$issues = [];
		$totalFiles = 0;
		$totalLines = 0;
		$filePath = $this->scanner->getCurrentFilePath();
		if (!$this->options->getOption('t') || in_array('syntax', $this->options->getOption('t'), true)) {
			$checkSyntax = true;
		} else {
			$checkSyntax = false;
		}

		while ($lines = $this->scanner->scanNextFile()) {
			$totalFiles++;

			//Check syntax and assign a line to grab if needed.
			$grabLineNumber = null;
			$grabLine = null;
			if ($checkSyntax) {
				$syntax = $this->tests->checkSyntax($filePath);
				if (!isset($syntax['is_valid'])) {
					$grabLineNumber = $syntax['line'];
				}
			}

			foreach ($lines as $index => $line) {
				$lineNumber = $index + 1;
				$line = trim($line, "\r\n");

				if ($lineNumber == $grabLineNumber) {
					$grabLine = $line;
				}

				$totalLines++;
				$issues = $this->tests->testLine($line);
				foreach ($issues as $section => $tests) {
					foreach ($tests as $test => $true) {
						$this->reporter->addToSection($section, $test, $filePath, $lineNumber, $line);
					}
				}
			}

			if ($checkSyntax && $grabLine !== null) {
				$this->reporter->addToSection('syntax', 'syntax', $filePath, $grabLineNumber, $grabLine.' //'.$syntax['error']);
			}
			$filePath = $this->scanner->getCurrentFilePath();
		}
		$this->reporter->add("Processed {$totalLines} lines contained in {$totalFiles} files.", 0, 1);
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
			return rtrim($_path, DIRECTORY_SEPARATOR);
		}
		return false;
	}
}
?>
