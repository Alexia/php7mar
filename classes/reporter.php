<?php
/**
 * PHP 7 MAR
 * Reporter Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar;

class reporter {
	/**
	 * Report folder to save reports
	 *
	 * @var		string
	 */
	private $reportFolder = '';

	/**
	 * Line Buffer
	 *
	 * @var		array
	 */
	private $buffer = [];

	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @param	string	Project file or folder
	 * @param	string	[Optional] Folder to save the report
	 * @return	void
	 */
	public function __construct($project, $reportFolder = null) {
		//A temporary variable needs to be used since realpath() will interpret null as the current path instead.
		$_reportFolder = realpath($reportFolder);
		if (!empty($reportFolder) || $_reportFolder !== false) {
			$this->reportFolder = $_reportFolder;
		} else {
			$this->reportFolder = PHP7MAR_DIR.DIRECTORY_SEPARATOR.'reports';
		}
	}
}
?>