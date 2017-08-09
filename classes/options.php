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
	 * Long(Two dashes, multiple letter) Options and
	 * Short(One dash, single letter) Options
	 *
	 * @var		array
	 */
	private $validOptions = [
		'f'	=> [
			'option'		=> self::OPTION_REQUIRED,
			'value' 		=> self::VALUE_REQUIRED,
			'comment'		=> '(Required) Path to the file or folder to run against.',
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
			'description'	=> 'By default all tests will run.  This option allows tests to be selected using a comma delimited list.',
			'example'		=> '-t="syntax,nuance"',
			'lowercase' => true,
			'comma_delimited'	=> true,
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
			'description'	=> 'A comma separated list of file extensions to consider as PHP files.  Defaults to "php"',
			'example'			=> '-x="php,inc"',
			'comma_delimited'	=> true
		],
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
		$short = '';
		$long = [];
		foreach( $this->validOptions as $o => $opt ){
			if( strlen( $o ) == 1  ){
				$short .= $o .($opt['value'] == self::VALUE_REQUIRED ?':':'').($opt['value'] == self::VALUE_OPTIONAL ?'::':'' );
			}else{
				$long[] = $o .($opt['value'] == self::VALUE_REQUIRED ?':':'').($opt['value'] == self::VALUE_OPTIONAL ?'::':'' );
			}
		}
		$this->options = getopt( $short, $long );
		if( !$this->options ){
			$this->printOptionsAndExit();
		}
		
		try{
			$this->enforceOptions();
		}catch( \Exception $e ){
			echo "\n-------------------------------------------\n";
			echo $e->getMessage();
			echo "\n-------------------------------------------\n";
			$this->printOptionsAndExit(false);
		}
	}

	/**
	 * Print out available options and exit.
	 *
	 * @access	private
	 * @return	void
	 */
	private function printOptionsAndExit( $verbose = true ) {
		echo "Available Options:\n";
		foreach ($this->validOptions as $option => $info) {
			$prefix = strlen( $option ) > 1 ? '--':'-';
		
			if( $verbose ){
				$format = "%s\t%s\n\t%s\n\tExample: %s\n";
				printf( $format, $prefix.$option, $info['comment'], $info['description'], $info['example'] );
				if( isset( $info['allowed'] )){
					echo "\tAvailable options: ".implode(', ', $info['allowed'] )."\n";
				}
			}else{
				$format = "%s\t%-50s\tExample: %s";
				printf( $format, $prefix.$option, $info['comment'], $info['example'] );
				if( isset( $info['allowed'] )){
					echo "\n\tAvailable options: ".implode(', ', $info['allowed'] );
				}
			}
			echo "\n";
			/*
			if( strlen( $option ) == 1  ){
				echo "-\033[1m{$option}\033[0m  {$info['comment']}\n  {}\n  Example: {$info['example']}\n";
			}else{
				echo "--\033[1m{$option}\033[0m  {$info['comment']}\n  {$info['description']}\n  Example: {$info['example']}\n";
			}
			*/
		}
		exit;
	}

	/**
	 * Enforce usage of required options.
	 *
	 * @access	private
	 * @return	void
	 */
	private function enforceOptions() {
		foreach( $this->validOptions as $option => $info ){
			if( isset($this->options[$option])){
				if( isset($info['lowercase']) && $info['lowercase'] ){
					$this->options[$option] = strtolower($this->options[$option]);
				}
				if( isset($info['comma_delimited']) && $info['comma_delimited'] ){
					$this->options[$option] = explode(',',$this->options[$option]);
				}
				if( isset($info['allowed']) ){
					$invalid = array_diff( $this->options[$option], $info['allowed'] );
					if( $invalid ){
						throw new \Exception("Invalid inputs for '{$option}': ".implode(',',$invalid));
					}
				}
			}elseif ($info['option'] === self::OPTION_REQUIRED ){
				throw new \Exception("The option `{$option}` is required.\n{$info['comment']}\n {$info['description']}\nExample: {$info['example']}\n");
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