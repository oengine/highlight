# Highlight
[![Latest Version on Packagist](https://img.shields.io/packagist/v/oengine/highlight.svg?style=flat-square)](https://packagist.org/packages/oengine/highlight)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/oengine/highlight/run-tests?label=tests)](https://github.com/oengine/highlight/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/oengine/highlight/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/oengine/highlight/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/oengine/highlight.svg?style=flat-square)](https://packagist.org/packages/oengine/highlight)

OEngine\Highlight is a PHP library for highlighting syntax that can be easily configured and extended.

The library parses the text, finds the tag \<pre>, read attributes (data-lang, data-file, data-theme), and for this reason decides how to highlight the syntax of this block.
Supports style customization.

Here are examples of styling:

<img width="757" height="309" src="images/phphighlight2.png">

## Requirements

PHP 7.1+

## Installation

You can install package via composer

```bash
$ composer require oengine/highlight
```

## Usage

```php
<?php

require_once 'vendor/autoload.php';

use OEngine\Highlight\FormatCode;

$text = '
<pre data-file="php-highlight/examples/index.php" data-lang="php">
abstract class AbstractClass
{
    /**
     * Our abstract method only needs to define the required arguments
     * @param string $name
     * @return string
     */
    abstract protected function prefixName(string $name): string;
}

class ConcreteClass extends AbstractClass
{
    /**
     * Our child class may define optional arguments not in the parent\'s signature
     * @param string $name
     * @param string $separator
     * @return string
     */
    public function prefixName(string $name, string $separator = ".") : string
    {
        if ($name == "Pacman") {
            $prefix = "Mr";
        } elseif ($name == "Pacwoman") {
            $prefix = "Mrs";
        } else {
            $prefix = "";
        }
        return "{$prefix}{$separator} {$name}";
    }
}

$class = new ConcreteClass;
echo $class->prefixName("Pacman"), "\n";
echo $class->prefixName("Pacwoman"), "\n";
</pre>
';

echo FormatCode::Highlight($text, 'railscasts',true,true);
```

### Language syntax support

- PHP
- JavaScript
- XML/HTML
- Bash
- Go
- and others

### Themes

- default
- darkula
- railscasts
- obsidian
- far
- vs2015
- c64

### Customization

```php
// Show line numbers
$highlighter->setShowLineNumbers(true);
// Show action panel
$highlighter->setShowActionPanel(true);
```

You can set following attributes in \<pre> tag
\<pre data-lang="php" data-file="example.php" data-theme="drakuala">

- lang - a language of the text. This affects how the parser will highlight the syntax.
- file - show file name in action panel.
- theme - allows to overwrite the global theme.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](./LICENSE.md)
