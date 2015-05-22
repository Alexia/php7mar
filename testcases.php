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
?>