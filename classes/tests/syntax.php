<?php
/**
 * PHP 7 MAR
 * MAR Syntax Tests Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar\tests;

class syntax {
	/**
	 * What test type this is, should be the same as the class name.
	 *
	 * @var		string
	 */
	private $testType = 'syntax';

	/**
	 * Tests to be registered.
	 *
	 * @var		array
	 */
	private $tests = [
		'syntax'
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
	 * Test
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _syntax($line) {
		return false;
	}
}
?>