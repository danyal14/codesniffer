# BetterCollective codesniffer

This repository contains the ruleset and codesniffer standard that is used when developing. The codesniffer will check all kind of file types an example list is:

* PHP
* Javscript
* HTML/XML
* CSS/SASS/Less

# Requirements

To run this codesniffer you need to install PHP_Codesniffer, see tutorial here:

For MAC/Unix/Linux:

* Install with brew: https://philsturgeon.uk/blog/2013/08/php-static-analysis-in-sublime-text/
* Install with pear: http://gielberkers.com/setup-php-codesniffer-phpstorm-osx/

For Windows:

* http://subharanjan.com/setup-php-codesniffer-windows-machine/

# Running the Codesniffer

To run the actual codesniffer run the following command in the terminal:

* phpcs --standard=BetterCollectiveCodeStandard/ruleset.xml path-to-project/

There is also a possibility to run it directly from PHPStorm:

* https://www.jetbrains.com/phpstorm/help/using-php-code-sniffer-tool.html

# What is the point of this?

One of the main reasons for having a codesniffer is to use the same code standard all over different projects. This streamlines the code and makes it easier for developers to find in the code but also gives you the feeling of that you know what to except. 

As an example you can always be sure that a variable should have a descriptive name instead of just one character:

Bad example: 
<pre>
$wsr = "Test";
</pre>

Good example: 
<pre>
$webServiceResult = 'Test'; 
</pre>

# Example

A list of code example standards can be found in the file: BetterCollectiveCodeStandard/Documentation.html
