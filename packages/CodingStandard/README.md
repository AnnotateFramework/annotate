[![Build Status](https://travis-ci.org/dotblue/codesniffer-ruleset.svg?branch=master)](https://travis-ci.org/dotblue/codesniffer-ruleset)

DotBlue CodeSniffer Standard
============================

This standard overrides some PSR-2 rules and adds some specific dotBlue rules.

Installation
------------

1. Register `dotblue/codesniffer-ruleset` manually in your `composer.json`.
	```
	"repositories": [
		{
			"type": "vcs",
			"url": "git@github.com:dotblue/codesniffer-ruleset.git"
		}
	]
	```

2. Install [squizlabs/PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).
	```
	composer require --dev squizlabs/php_codesniffer
	```

3. Install `dotblue/codesniffer-ruleset`.
	```
	composer require --dev dotblue/codesniffer-ruleset:@dev
	```

Usage
-----

Run following command with `<target>` replaced by path to your source code:

```
vendor/bin/phpcs <target> --standard=vendor/dotblue/codesniffer-ruleset/DotBlue/ruleset.xml
```

Pro installation tip
-------------------
In case you installed CodeSniffer globally PEAR/composer you can improve your experience.
After cloning repository you can symlink directory to 'install' code standard to sniffer:

`ln -s PATH_TO_CLONED_DIR PATH_TO_CODE_SNIFFER_INSTALLATION/Standards/DotBlue`
 
Verify it is installed with `phpcs -i` (you should see DotBlue in output). And use it with just `--standard=DotBlue`

Creating rules
--------------

Make a class in Sniffs directory and use PSR-0 with `DotBlue` root namespace;

Filename: `Sniffs/Classes/ClassDeclarationSniff`

Classname: `DotBlue\Sniffs\Classes\ClassDeclarationSniff`

**Please add documentation file for added/edited rules.**

Testing
-------

Uses nette/tester. Create test in tests folder - see any test as example.
Conventions:

- Test filename is as same as sniff name
- Always create php file in tests/invalid and tests/valid folders to test the sniff

Differences from PSR-2
----------------------

- We use tabs
- We do not force line length - use your brain and common sense
- We force uppercase constants (incl. TRUE, FALSE, NULL)
- Namespace declaration
	- use 1 empty line in case any use statement follows
	- use 2 empty lines in case no use statement follows
- We force one empty line after class body
- Empty class must be completely inline
- Visibility
	- must not be declared on interface's method
	- must be declared on class's method
- Variable names in method's DocBlock are not allowed
- There must be exactly two spaces between `@param` and type definition
- There must be exactly three empty lines between methods
- There must always be parentheses after constructor call.
- Force one space between `@return` and type.
- Enable inline methods in case there is no method body.
- Usage of 'boolean' in typehints is forbidden. Use 'bool' only.
- Disabled absolute namespace usage. Import everything with 'use' statement
- There must be exactly one space between `&` and variable name
- Forbidden calls of functions `d`, `dump`, `var_dump`
- Ruleset can find unused private properties
	- even with aliased $this
	- even static properties (self::, ClassName::)

Generating documentation
------------------------

```
vendor/bin/phpcs --standard=vendor/bin/dotblue/codesniffer-ruleset/DotBlue --generator=HTML > cs-docs.html
```

Todo
----

See [issues](https://github.com/dotblue/CodeSnifferStandard/issues)
