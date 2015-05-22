<?php
$$foo['bar']['baz'];	// interpreted as ($$foo)['bar']['baz']
$foo->$bar['baz'];		// interpreted as ($foo->$bar)['baz']
$foo->$bar['baz']();	// interpreted as ($foo->$bar)['baz']()
Foo::$bar['baz']();		// interpreted as (Foo::$bar)['baz']()
global $$foo->bar;
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
?>