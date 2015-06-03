<?php
/**
 * PHP 7 MAR
 * Options Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace alexia\mar;

class options {
	/**
	 * Option Optional
	 *
	 * @var		constant
	 */
	const OPTION_OPTIONAL = 1;

	/**
	 * Option Required
	 *
	 * @var		constant
	 */
	const OPTION_REQUIRED = 2;

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
	const VALUE_REQUIRED = 2;

	/**
	 * Short(One dash, single letter) Options
	 *
	 * @var		array
	 */
	private $validShortOptions = [
		'f'	=> [
			'option'		=> self::OPTION_REQUIRED,
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> 'Path to the file or folder to run against.',
			'description'	=> 'The location of the file or folder to use for generating the report.  A fully qualified path is recommended.  Relative paths will be based off the php7mar folder.',
			'example'		=> '-f="/path/to/folder"'
		],
		'r'	=> [
			'option'		=> self::OPTION_OPTIONAL,
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> 'Path to the folder to save the report.',
			'description'	=> 'The location to save the final report.  By default this saves into the reports/ folder inside the php7mar folder.  A fully qualified path is recommended.  Relative paths will be based off the php7mar folder.',
			'example'		=> '-r="/path/to/folder"'
		],
		't'	=> [
			'option'		=> self::OPTION_OPTIONAL,
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> 'Types of tests to run.',
			'description'	=> 'By default all tests will run.  This option allows tests to be selected using a comma delimited list.  Allowable values: critical, nuance, and syntax.',
			'example'		=> '-t="syntax,nuance"',
			'allowed'		=> [
				'critical',
				'nuance',
				'syntax'
			]
		],
		'x' => [
			'option'			=> self::OPTION_OPTIONAL,
			'value'				=> self::VALUE_REQUIRED,
			'comment'			=> 'File extensions to include when scanning a directory.',
			'description'		=> 'A comma separated list of file extensions to consider as PHP files.  Defaults to "php"',
			'example'			=> '-x="php,inc"',
			'comma_delimited'	=> true
		]
	];

	/**
	 * Long(Two dashes, multiple letter) Options
	 *
	 * @var		array
	 */
	private $validLongOptions = [
		'php'	=> [
			'option'		=> self::OPTION_OPTIONAL,
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> 'File path to the PHP binary to use for syntax checking.',
			'description'	=> 'If this option is not used syntax checking will use the default PHP installtion to test syntax.',
			'example'		=> '--php="/path/to/php/binary/php"'
		],
		/*'format'	=> [
			'option'		=> self::OPTION_OPTIONAL,
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> 'Format of the report output.',
			'description'	=> 'By default the report will be formatted with HTML.  Valid formats are: plain, markdown, html',
			'example'		=> '--format="markdown"',
			'allowed'		=> [
				'plain',
				'markdown',
				'html'
			]
		]*/
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

		if (empty($options)) {
			$this->printOptionsAndExit();
		}

		foreach ($options as $option) {
			$this->parseOption($option);
		}
		$this->enforceOptions();
	}

	/**
	 * Print out available options and exit.
	 *
	 * @access	private
	 * @return	void
	 */
	private function printOptionsAndExit() {
		echo "Available Options:\n";
		foreach ($this->validShortOptions as $option => $info) {
			echo "-\033[1m{$option}\033[0m\n	{$info['comment']}\n	{$info['description']}\n		Example: {$info['example']}\n\n";
		}
		foreach ($this->validLongOptions as $option => $info) {
			echo "--\033[1m{$option}\033[0m\n	{$info['comment']}\n	{$info['description']}\n		Example: {$info['example']}\n\n";
		}
		exit;
	}

	/**
	 * Parse a raw option
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
					$validOptions = $this->validShortOptions;
				} elseif (strlen($option) >= 2) {
					//Long Option
					$validOptions = $this->validLongOptions;
				}
				if (!isset($validOptions[$option])) {
					die("The option `{$option}` does not exist.\n");
				}
				if ($validOptions[$option]['value'] === self::VALUE_REQUIRED && !isset($value)) {
					die("The option `{$option}` requires a value, but none was given.\n");
				}
				if (isset($validOptions[$option]['allowed']) || (isset($validOptions[$option]['comma_delimited']) && $validOptions[$option]['comma_delimited'] == true)) {
					$value = explode(',', $value);
					$value = array_map('trim', $value);
				}
				if (isset($validOptions[$option]['allowed'])) {
					foreach ($value as $_value) {
						if (!in_array($_value, $validOptions[$option]['allowed'])) {
							die("The value `{$_value}` for `{$option}` is not valid.\n");
						}
					}
				}
				$this->options[$option] = (isset($value) ? $value : true);
			}
		}
	}

	/**
	 * Enforce usage of required options.
	 *
	 * @access	private
	 * @return	void
	 */
	private function enforceOptions() {
		foreach ($this->validShortOptions as $option => $info) {
			if ($info['option'] === self::OPTION_REQUIRED && !isset($this->options[$option])) {
				die("The option `{$option}` is required to be given.\n	{$info['comment']}\n	{$info['description']}\n	Example: {$info['example']}\n");
			}
		}
		foreach ($this->validLongOptions as $option => $info) {
			if ($info['option'] === self::OPTION_REQUIRED && !isset($this->options[$option])) {
				die("The option `{$option}` is required to be given.\n	{$info['comment']}\n	{$info['description']}\n	Example: {$info['example']}\n");
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
		if (!array_key_exists($option, $this->options)){
			return false;
		}
		return $this->options[$option];
	}
}
?>