Silex Markdown Provider
===============

[![Latest Stable Version](https://poser.pugx.org/nachonerd/silex-markdown-provider/v/stable)](https://packagist.org/packages/nachonerd/silex-markdown-provider)
[![Total Downloads](https://poser.pugx.org/nachonerd/silex-markdown-provider/downloads)](https://packagist.org/packages/nachonerd/silex-markdown-provider)
[![Latest Unstable Version](https://poser.pugx.org/nachonerd/silex-markdown-provider/v/unstable)](https://packagist.org/packages/nachonerd/silex-markdown-provider) [![License](https://poser.pugx.org/nachonerd/silex-markdown-provider/license)](https://packagist.org/packages/nachonerd/silex-markdown-provider)
[![Build Status](https://travis-ci.org/nachonerd/silex-markdown-provider.svg?branch=master)](https://travis-ci.org/nachonerd/silex-markdown-provider)
[![Code Climate](https://codeclimate.com/github/nachonerd/silex-markdown-provider/badges/gpa.svg)](https://codeclimate.com/github/nachonerd/silex-markdown-provider)
[![Test Coverage](https://codeclimate.com/github/nachonerd/silex-markdown-provider/badges/coverage.svg)](https://codeclimate.com/github/nachonerd/silex-markdown-provider/coverage)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg?style=flat-square)](https://php.net/)

### Description
Silex Service Provider using [cebe/markdown](http://markdown.cebe.cc/). cebe/markdown was created by [Carsten Brandt](http://cebe.cc/about).

### License
GNU GPL v3

### Requirements
- [PHP version 5.4](http://php.net/releases/5_4_0.php)
- [Composer](https://getcomposer.org/)
- [SILEX](http://silex.sensiolabs.org/)
- [Synfony Finder](http://symfony.com/doc/current/components/finder.html)
- [cebe/markdown](http://markdown.cebe.cc/)
- [PHP Unit 4.7.x](https://phpunit.de/) (optional)
- [PHP_CodeSniffer 2.x](http://pear.php.net/package/PHP_CodeSniffer/redirected) (optional)

### Install

```
composer require nachonerd/silex-markdown-provider
```

### Usage

Include following line of code somewhere in your initial Silex file (index.php or whatever):

```php
...
$app->register(
    new \NachoNerd\Silex\Markdown\Provider(),
    array(
        "nn.markdown.path" => __DIR__,
        "nn.markdown.flavor" => 'extra',
        "nn.markdown.filter" => '/\.md/'
    )
);
...
```
Now you have access to instance of cebe/markdown throw $app['nn.markdown'].

### Twig Extension

#### Filters

- **markdown_parse**
Parse specified text to html

```twig

{{ "## Some text"|markdown_parse }}

.....

{{ texttoparse|markdown_parse }}

```

- **markdown_parse_file**
Parse specified file to html

```twig

{{ "some/file.md"|markdown_parse_file }}

.....

{{ filename|markdown_parse_file }}

```

#### Functions

- **markdown_parse_last_file**
Parse last file in markdown,path directory to html

```twig

{{ markdown_parse_last_file() }}

```

### Example

```php
<?php
    require_once __DIR__.'/../vendor/autoload.php';

    $app = new Silex\Application();

    // Considering the config.yml files is in the same directory as index.php
    $app->register(
        new \NachoNerd\Silex\Finder\Provider(),
        array(
            "nn.markdown.path" => __DIR__,
            "nn.markdown.flavor" => original,
            "nn.markdown.filter" => '/\.md/'
        )
    );
    $app->boot();

    ...
    // traditional markdown and parse full text
    $parser = $app[nn.markdown];
    $hmtl = $parser->parse($markdown);
    $hmtl = $parser->parseFile($filename);
    ...
```
