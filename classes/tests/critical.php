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
		'variableInterpolation',
		'duplicateFunctionParameter',
		'reservedNames',
		'deprecatedFunctions'
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
		$regex = "#(?:(?:->|\\$)\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]+|::\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]+\[.+?\]\(.*?\))#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Functions can no longer define parameters with the same name more than once.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _duplicateFunctionParameter($line) {
		$regex = "#function [a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]+\((.*?)(?:\)(?!(?:\s*?,|\s*?\\$)))#i";
		if (preg_match($regex, $line, $matches)) {
			//Abuse the lexer to get variables out.
			$tokens = token_get_all("<?php ".$matches[1]."?>");
			$variables = [];
			foreach ($tokens as $token) {
				if (!is_array($token)) {
					continue;
				}
				if ($token[0] == T_VARIABLE) {
					$variables[] = $token[1];
				}
			}

			$totalArguments = count($variables);
			$variables = array_unique($variables);
			$uniqueArguments = count($variables);
			if ($totalArguments != $uniqueArguments) {
				return true;
			}
		}
		return false;
	}

	/**
	 * New class names that are reserved for internal PHP classes.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _reservedNames($line) {
		$regex = "#(?:^|\s)(class|interface|trait)\s+?(?:bool|int|float|string|null|false|true|resource|object|mixed|numeric)(?:$|\s|{)#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * Functions deprecated and removed.
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _deprecatedFunctions($line) {
		$regex = "#(?:mcrypt_generic_end|mcrypt_ecb|mcrypt_cbc|mcrypt_cfb|mcrypt_ofb|set_magic_quotes_runtime|magic_quotes_runtime|set_socket_blocking)\(#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}
}
?>