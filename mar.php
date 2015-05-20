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
	 * Main Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		define('PHP7MAR_DIR', __DIR__);
		spl_autoload_register([self, 'autoloader'], true, false);

		$this->options = new options();

		$this->reporter = new reporter($this->options->getOption('f'), $this->options->getOption('r'));
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
}
$mar = new \mar\main();
?>