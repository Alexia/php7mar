<?php
/**
 * PHP 7 MAR
 * MAR Critical Tests Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar\tests;

class critical {
	/**
	 * What test type this is, should be the same as the class name.
	 *
	 * @var		string
	 */
	private $testType = 'critical';

	/**
	 * Tests to be registered.
	 *
	 * @var		array
	 */
	private $tests = [
		'variableInterpolation'
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
	 * Find cases of "$$variable" that need curly braces added.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _variableInterpolation($line) {
		$regex = "#(?:::|->|\\$)\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}
}
?>