<?php
/**
 * PHP 7 MAR
 * Options Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace mar;

class options {
	/**
	 * Value None
	 *
	 * @var		constant
	 */
	const VALUE_NONE = 0;

	/**
	 * Value Optional
	 *
	 * @var		constant
	 */
	const VALUE_OPTIONAL = 1;

	/**
	 * Value Required
	 *
	 * @var		constant
	 */
	const VALUE_REQUIRED = 1;

	/**
	 * Short(One dash, single letter) Options
	 *
	 * @var		array
	 */
	private $shortOptions = [
		'f'	=> [
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> 'Path to the file or folder to run against.',
			'description'	=> 'The location of the file or folder to use for generating the report.  A fully qualified path is recommended.  Relative paths will be based off the php7mar folder.'
		],
		'r'	=> [
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> 'Path to the folder to save the report.',
			'description'	=> 'The location to save the final report.  By default this saves into the reports/ folder inside the php7mar folder.  A fully qualified path is recommended.  Relative paths will be based off the php7mar folder.'
		]
	];

	/**
	 * Validated Options
	 *
	 * @var		array
	 */
	private $options = [];

	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		global $argv;

		//Temporary variable so we do not override the global.
		$_argv = $argv;
		array_shift($_argv);
		$options = $_argv;

		foreach ($options as $option) {
			$this->parseOption($option);
		}

		var_dump($options);
		if ($options !== false) {
			$this->options = $options;
		}
	}

	/**
	 * Function Documentation
	 *
	 * @access	private
	 * @param	string	Raw option from the command line.
	 * @return	array	Option name, value if provided.
	 */
	private function parseOption($rawOption) {
		$regex = "#^(?P<option>-[a-zA-Z]{1}|--[a-zA-Z-]{2,})(?:=(?P<value>['|\"]?.+?['|\"]?))?$#";
		if (preg_match($regex, trim($rawOption), $matches)) {
			if (isset($matches['option'])) {
				$option = ltrim($matches['option'], '-');
				if (isset($matches['value'])) {
					$value = $matches['value'];
				}

				if (strlen($option) == 1) {
					//Short Option
					if (!isset($this->shortOptions[$option])) {
						echo "The option -{$option} does not exist.\n";
						exit;
					}
					if ($this->shortOptions[$option]['value'] === self::VALUE_REQUIRED && !$value) {
						echo "The option -{$option} requires a value, but none was given.\n";
						exit;
					}
				} elseif (strlen($option) >= 2) {
					//Long Option
					if (!isset($this->longOptions[$option])) {
						echo "The option --{$option} does not exist.\n";
						exit;
					}
				}
			}
		}
	}

	/**
	 * Return the value for the given option.
	 *
	 * @access	public
	 * @param	string	Short or Long Option
	 * @return	mixed
	 */
	public function getOption($option) {
		return $this->options[$option];
	}
}
?>