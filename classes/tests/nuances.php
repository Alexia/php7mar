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
		'yield'
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
}
?>