<?php
$$foo['bar']['baz'];	//Interpreted as ($$foo)['bar']['baz']
$foo->$bar['baz'];		//Interpreted as ($foo->$bar)['baz']
$foo->$bar['baz']();	//Interpreted as ($foo->$bar)['baz']()
Foo::$bar['baz']();		//Interpreted as (Foo::$bar)['baz']()
global $$foo->bar;		//The global keyword now only accepts simple variables.
$array["a"] =& $array["b"]; //Array value created by reference.
list() = "string";
list() = $a;
list(,,) = $a;
list($x, list(), $y) = $a;
foreach ($array as &$val) { /*...*/ }
foreach ($array as &$val => $key) { /*...*/ }
foreach ($array as getThing() => &$val) { /*...*/ }
function foo($a, $b, $unused, $unused) { /*...*/ }
static function renderArrayMap(&$parser, $value = '', $delimiter = ',', $var = 'x', $formula = 'x', $new_delimiter = ', ') { /*...*/ }
var_dump(func_get_args(0));
"0x123";
"0x123fea";
"\u{00f0}";
"\u{xyz}";
"\\u{xyz}";

//Reserved Class/Interface/Trait Names
} class bool { /*...*/ } //Should not get caught, along with this: class object
class bool { /*...*/ }
class int { /*...*/ }
class float { /*...*/ }
class string { /*...*/ }
class null { /*...*/ }
class false { /*...*/ }
class true { /*...*/ }
class resource { /*...*/ }
class object { /*...*/ }
class mixed { /*...*/ }
class numeric { /*...*/ }
//@param $class string the class name

interface bool { /*...*/ }
interface int { /*...*/ }
interface float
interface string {
	/*...*/
}
interface null 
{
	/*...*/
}
interface false { /*...*/ }
interface true { /*...*/ }
interface resource { /*...*/ }
interface object { /*...*/ }
interface mixed { /*...*/ }
interface numeric { /*...*/ }

trait bool { /*...*/ }
trait int { /*...*/ }
trait float { /*...*/ }
trait string { /*...*/ }
trait null { /*...*/ }
trait false { /*...*/ }
trait true { /*...*/ }
trait resource { /*...*/ }
trait object { /*...*/ }
trait mixed { /*...*/ }
trait numeric { /*...*/ }

//Deprecated and Removed Functions
mcrypt_generic_end();
mcrypt_ecb();
mcrypt_cbc();
mcrypt_cfb();
mcrypt_ofb();
set_magic_quotes_runtime();
magic_quotes_runtime();
set_socket_blocking();
mysql_affected_rows();
not_mysql_affected_rows();
mysql_client_encoding();
mysql_close();
mysql_connect();
mysql_create_db();
mysql_data_seek();
mysql_db_name();
mysql_db_query();
mysql_drop_db();
mysql_errno();
mysql_error();
mysql_escape_string();
mysql_fetch_array();
mysql_fetch_assoc();
mysql_fetch_field();
mysql_fetch_lengths();
mysql_fetch_object();
mysql_fetch_row();
mysql_field_flags();
mysql_field_len();
mysql_field_name();
mysql_field_seek();
mysql_field_table();
mysql_field_type();
mysql_free_result();
mysql_get_client_info();
mysql_get_host_info();
mysql_get_proto_info();
mysql_get_server_info();
mysql_info();
mysql_insert_id();
mysql_list_dbs();
mysql_list_fields();
mysql_list_processes();
mysql_list_tables();
mysql_num_fields();
mysql_num_rows();
mysql_pconnect();
mysql_ping();
mysql_query();
mysql_real_escape_string();
mysql_result();
mysql_select_db();
mysql_set_charset();
mysql_stat();
mysql_tablename();
mysql_thread_id();
mysql_unbuffered_query();

// New objects cannot be assigned by reference
class C {}
$c =& new C;
$c =&new C;

// Methods with the same name as their class will not be constructors in a future version of PHP
class FooBar {
	var $test = 42;

	function set() {}

	function FooBar() {
		// NOP
	}
}

class FooBar
{
	var $test = 42;

	function set()
	{
		
	}

	function FooBar() 
	{
		// NOP
	}
}

	abstract class TheThing {
		public function TheThing() {
		}
	}
