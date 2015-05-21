<?php
/**
 * PHP 7 MAR
 * MAR Tests Class
 * Base class for MAR tests including common regular expressions and base logic.
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar;

class tests {
	/**
	 * Available test types.
	 *
	 * @var		array
	 */
	private $testTypes = [
		'critical'	=> null,
		'nuances'	=> null,
		'syntax'	=> null
	];

	/**
	 * Common Regular Expressions used in tests.
	 *
	 * @var		array
	 */
	private $commonRegex = [
		'variable'	=> '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*'
	];

	/**
	 * Registered Tests, Callables
	 *
	 * @var		array
	 */
	private $tests = [];

	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @param	array	[Optional] Test Types to Run
	 * @return	void
	 */
	public function __construct($testTypes = []) {
		if (!is_array($testTypes) && !empty($testTypes)) {
			throw new \Exception(__METHOD__.": Invalid test types variable passed.");
		} elseif (!empty($testTypes)) {
			$testTypes = array_flip($testTypes);
			$this->testTypes = array_intersect_key($testTypes, $this->testTypes);
		}
		foreach ($this->testTypes as $testType => $className) {
			$className = $this->getTestClassName($testType);
			$this->testTypes[$testType] = new $className();
			$this->registerTests($testType, $this->testTypes[$testType]->getTests());
		}
	}

	/**
	 * Get the class name for this test type.
	 *
	 * @access	public
	 * @param	string	Test Type
	 * @return	string	Test Class Name
	 */
	public function getTestClassName($testType) {
		return "mar\\tests\\".$testType;
	}

	/**
	 * Register test(s);
	 *
	 * @access	public
	 * @param	string	Test Type
	 * @param	array	Tests to Register
	 * @return	void
	 */
	public function registerTests($testType, $tests) {
		foreach ($tests as $test) {
			$this->tests[] = [
				'type'		=> $testType,
				'test'		=> $test,
				'callable'	=> [$this->testTypes[$testType], '_'.$test]
			];
		}
	}

	/**
	 * Test the line of code and return issues found.
	 *
	 * @access	public
	 * @param	string	Line of code
	 * @return	array	Any issues found
	 */
	public function testLine($line) {
		$issues = [];
		foreach ($this->tests as $info) {
			$fail = call_user_func($info['callable'], $line);
			if ($fail) {
				$issues[$info['type']][$info['test']] = $fail;
			}
		}
		return $issues;
	}
}
?>