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
		'critical',
		'nuances',
		'syntax'
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
			$this->testTypes = array_intersect($testTypes, $this->testTypes);
		}
		foreach ($this->testTypes as $testType) {
			$className = 'mar\tests\\'.$testType;
			$this->tests[$testType] = new $className();
		}
	}
}
?>