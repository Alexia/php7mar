<?php
/**
 * PHP 7 MAR
 * MAR Nuance Tests Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace alexia\mar\tests;

class nuance {
	/**
	 * What test type this is, should be the same as the class name.
	 *
	 * @var		string
	 */
	private $testType = 'nuance';

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
		'foreachByReference',
		'funcGetArg',
		'hexadecimalString',
		'unicode'
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
		$regex = "#^\s*?list\(.*?\)\s+?=\s+?['|\"].*?['|\"]#i";
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

	/**
	 * Using func_get_arg()/func_get_args() after modifying a parameter passed to a function will now return the modified value.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _funcGetArg($line) {
		$regex = "#func_get_args?\(#";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Hexadecimals in strings are no longer treated as integers.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _hexadecimalString($line) {
		$regex = "#['|\"]0x[a-fA-F0-9]+?['|\"]#";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Make sure none of the code is using the new unicode format(\u{xxxxxx}) accidentally.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _unicode($line) {
		$regex = "#(?:(?:[^\\\]|^)\\\)u{.*?[^a-fA-F0-9].*?}#";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}
}
?>