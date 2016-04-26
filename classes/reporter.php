<?php
/**
 * PHP 7 MAR
 * Reporter Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace alexia\mar;

class reporter {
	/**
	 * Project File or Path
	 *
	 * @var		string
	 */
	private $projectPath = null;

	/**
	 * Report folder to save reports
	 *
	 * @var		string
	 */
	private $reportFolder = null;

	/**
	 * Full file path to the report file.
	 *
	 * @var		string
	 */
	private $fullFilePath = null;

	/**
	 * Line Buffer, contained in keyed sections.
	 *
	 * @var		array
	 */
	private $sectionBuffers = [];

	/**
	 * Start Time, date('U')
	 *
	 * @var		string
	 */
	private $startTime = null;

	/**
	 * File Handler Resource
	 *
	 * @var		string
	 */
	private $file;

	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @param	string	Project file or folder
	 * @param	string	[Optional] Folder to save the report
	 * @return	void
	 */
	public function __construct($projectPath, $reportFolder = null) {
		$this->startTime = time();

		if (empty($projectPath)) {
			throw new \Exception(__METHOD__.": Project path given was empty.");
		}
		$this->projectPath = $projectPath;

		$reportFolder = main::getRealPath($reportFolder);
		if ($reportFolder !== false) {
			$this->reportFolder = $reportFolder;
		} else {
			$this->reportFolder = PHP7MAR_DIR.DIRECTORY_SEPARATOR.'reports';
		}
		$this->fullFilePath = $this->reportFolder.DIRECTORY_SEPARATOR.date('Y-m-d H.i.s ').basename($this->projectPath, '.php').".md";

		$this->file = fopen($this->fullFilePath, 'w+');
		register_shutdown_function([$this, 'onShutdown']);

		$this->add(date('c', $this->startTime), 0, 1);
		$this->add("Scanning {$this->projectPath}", 0, 1);
	}

	/**
	 * Add a new line to the report.
	 *
	 * @access	public
	 * @param	string	Line of text to add to the buffer.
	 * @param	integer Number of new line characters to add before the line.
	 * @param	integer Number of new line characters to add after the line.
	 * @param	string	Line of text to add to the buffer.
	 * @return	void
	 */
	public function add($line, $nlBefore = 0, $nlAfter = 0) {
		$output = str_repeat("\n", $nlBefore).$line.str_repeat("\n", $nlAfter);
		if (fwrite($this->file, $output) === false) {
			die("There was an error attempting to write to the report file.\n".$this->fullFilePath."\n");
		}
	}

	/**
	 * Add text to the specified section.
	 *
	 * @access	public
	 * @param	string	Section Name
	 * @param	string	Test Name
	 * @param	string	File Path
	 * @param	string	Line Number
	 * @param	string	Code Line
	 * @return	void
	 */
	public function addToSection($section, $test, $filePath, $lineNumber, $codeLine) {
		if (empty($section)) {
			throw new \Exception(__METHOD__.": The section can not be empty.");
		}
		$this->sectionBuffers[$section][$filePath][$test][] = [$lineNumber, $codeLine];
	}

	/**
	 * Add sections in the buffer to the output.
	 *
	 * @access	public
	 * @return	void
	 */
	public function addSections() {
		foreach ($this->sectionBuffers as $section => $filePaths) {
			$this->add('# '.$section, 1, 1);
			foreach ($filePaths as $filePath => $tests) {
				$this->add('#### '.$filePath, 0, 1);
				foreach ($tests as $test => $lines) {
					$this->add('* '.$test, 0, 1);
					foreach ($lines as $line) {
						$this->add(" * Line {$line[0]}: `".str_replace('`', '\`', $line[1])."`", 0, 1);
					}
				}
				$this->add('', 1, 0);
			}
		}
	}

	/**
	 * Return the file path of the report.
	 *
	 * @access	public
	 * @return	string	File Path
	 */
	public function getReportFilePath() {
		return $this->fullFilePath;
	}

	/**
	 * Handle any file clean up on shutdown.
	 *
	 * @access	public
	 * @return	void
	 */
	public function onShutdown() {
		fclose($this->file);
	}
}
?>