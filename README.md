#Introduction
####What is PHP 7 Migration Assistant Report(MAR)?
PHP 7 MAR, or just "php7mar", is a command line utility to generate reports on existing PHP 5 to assist developers in porting their code quickly.  It will run against invididual files or entire project folders.  Reports contain line numbers, issues noted, and suggested fixes along with documentation links.

#Usage
First, start by downloading or cloning this repository.  It does not need to be placed inside the folder containing the source code.

To begin, type on the command line:

	php mar.php

This will produce a list of available arguments and switches.

Typical usage would appear as:

	php mar.php /path/to/file/example.php

Or:

	php mar.php /path/to/folder/example/
