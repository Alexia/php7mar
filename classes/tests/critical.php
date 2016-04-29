<?php
/**
 * PHP 7 MAR
 * MAR Critical Tests Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace alexia\mar\tests;

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
		'deprecatedFunctions',
		'newOperatorWithReference',
		'oldClassConstructors',
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
		$regex = "#(((::|->)\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*)+\\[[^\s]+\\]\()|(->\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*\\[)|(\\$\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*\\[)|(^\s*?global.*?\\$\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*)#i"; //@LawnGnome's regex from Twitter; modified and extended to support global simple variable change.
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
		$regex = "#^\s*?(class|interface|trait)\s+?(?:bool|int|float|string|null|false|true|resource|object|mixed|numeric)(?:$|\s|{)#i";
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
		$regex = "#(?:mysql_affected_rows|mysql_client_encoding|mysql_close|mysql_connect|mysql_create_db|mysql_data_seek|mysql_db_name|mysql_db_query|mysql_drop_db|mysql_errno|mysql_error|mysql_escape_string|mysql_fetch_array|mysql_fetch_assoc|mysql_fetch_field|mysql_fetch_lengths|mysql_fetch_object|mysql_fetch_row|mysql_field_flags|mysql_field_len|mysql_field_name|mysql_field_seek|mysql_field_table|mysql_field_type|mysql_free_result|mysql_get_client_info|mysql_get_host_info|mysql_get_proto_info|mysql_get_server_info|mysql_info|mysql_insert_id|mysql_list_dbs|mysql_list_fields|mysql_list_processes|mysql_list_tables|mysql_num_fields|mysql_num_rows|mysql_pconnect|mysql_ping|mysql_query|mysql_real_escape_string|mysql_result|mysql_select_db|mysql_set_charset|mysql_stat|mysql_tablename|mysql_thread_id|mysql_unbuffered_query|mcrypt_generic_end|mcrypt_ecb|mcrypt_cbc|mcrypt_cfb|mcrypt_ofb|set_magic_quotes_runtime|magic_quotes_runtime|set_socket_blocking)\(#i";
		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	/**
	 * New objects cannot be assigned by reference
	 *
	 * @access	public
	 * @param	string	Line to test against.
	 * @return	boolean	Line matches test.
	 */
	public function _newOperatorWithReference($line) {
		$regex = "#&\s?new\s#";

		if (preg_match($regex, $line)) {
			return true;
		}
		return false;
	}

	public function _oldClassConstructors($line) {
		static $lastClassName = false;

		// reset the name of the class that we've seen
		if ($line === '<?php') {
			$lastClassName = false;
		}

		// find the start of PHP class declaration
		if (preg_match('#^\s?(abstract\s+)?class (\w+)#', $line, $matches)) {
			$lastClassName = $matches[2];
		}

		// is the class name used as the function name?
		if ($lastClassName !== false && strpos($line, 'function') !== false) {
			if (preg_match("#function {$lastClassName}\s?\(#", $line)) {
				return true;
			}
		}

		return false;
	}
}
?>
