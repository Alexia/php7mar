#(DEVELOPMENT CODE - Expect everything to change and break.)

#Introduction
####What is PHP 7 Migration Assistant Report(MAR)?
PHP 7 MAR, or just "php7mar", is a command line utility to generate reports on existing PHP 5 to assist developers in porting their code quickly.  It will run against invididual files or entire project folders.  Reports contain line numbers, issues noted, and suggested fixes along with documentation links.

####Will php7mar automatically fix my code?
No, php7mar does implement a full lexer to determine code changes and can not determine the intent of the code.  It uses lexer tokenizing, string matching, and regular expressions to find syntax changes that may cause issues when porting code to PHP 7.  As well, it will detect code snippets in comments and report them as it can not distinguish it as commented code.

#Usage
First, start by downloading or cloning this repository.  It does not need to be placed inside the folder containing the source code.

To begin, type on the command line:

	php mar.php

This will produce a list of available arguments and switches.

Typical usage would appear as:

	php mar.php -f="/path/to/file/example.php"

Or:

	php mar.php -f="/path/to/folder/example/"

This would run against the example file or folder and save the resulting report into a reports folder inside the php7mar folder.  When referencing the file or folder to run against it is recommend to use a fully qualified path.  Relative paths are supported, but will be relative to the location of the php7mar folder.

#Test Types
##Critical
Critical tests look for issues that will cause broken code, compilation errors, or otherwise create code that works in unintended manors.

##Nuances
Nuance tests look for issues that might cause silent underisable code behavior.  These tests can report many false positives as they can not determine the intent of the code being checked.

##Syntax
A basica syntax checker that tests all files for standard syntax issues.  This is useful for double checking work after making many mass find and replace operations.