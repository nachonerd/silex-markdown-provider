Silex Markdown Provider
===============

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg?style=flat-square)](https://php.net/)

### Description
Silex Service Provider using [cebe/markdown](http://markdown.cebe.cc/)

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
$app->register(new \NachoNerd\Silex\Markdown\Provider());
...
```
Now you have access to instance of cebe/markdown throw $app['nn.markdown'].

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