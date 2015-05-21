<?php
/**
 * PHP 7 MAR
 * MAR Nuances Tests Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar\tests;

class nuances {
	/**
	 * What test type this is, should be the same as the class name.
	 *
	 * @var		string
	 */
	private $testType = 'nuances';

	/**
	 * Tests to be registered.
	 *
	 * @var		array
	 */
	private $tests = [
		'yield',
		'arrayValueByReference',
		'listUnpackString',
		'emptyListAssignment',
		'foreachByReference'
	];

	/**
	 * Get all tests for this test type.
	 *
	 * @access	public
	 * @return	array	Tests
	 */
	public function getTests() {
		return $this->tests;
	}

	/**
	 * Find cases of "yield" that need parenthesis added.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _yield($line) {
		$regex = "#^\s*?yield#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Find cases of an array value created by a reference.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _arrayValueByReference($line) {
		$regex = "#\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]+?\[.+?\]\s*?=&#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Empty list() assignment now results in null.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _emptyListAssignment($line) {
		$regex = "#(?:^\s*?list\(\s*?\)|list\([,|\s]+?\)|list\(.*?list\([,|\s]*?\).*?\))#";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Invalid usage of list() to unpack a string.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _listUnpackString($line) {
		$regex = "#list\(.*?\)\s+?=\s+?['|\"].*?['|\"]#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Using variables by reference in a foreach statement no longer works the same way.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _foreachByReference($line) {
		$regex = "#foreach\s+?\(\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]+\s+?as\s+.*?&\\$.*?\)#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}
}
?>