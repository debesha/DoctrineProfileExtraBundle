# DoctrineProfileExtraBundle

[![Latest Stable Version](https://img.shields.io/packagist/v/debesha/doctrine-hydration-profiler-bundle.svg?style=flat-square)](https://packagist.org/packages/debesha/doctrine-hydration-profiler-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/debesha/doctrine-hydration-profiler-bundle.svg?style=flat-square)](https://www.php.net/releases/)
[![Symfony](https://img.shields.io/badge/Symfony-5.4%20|%206.x%20|%207.x-000000?logo=symfony&logoColor=white&style=flat-square)](https://symfony.com/releases)
[![Doctrine ORM](https://img.shields.io/badge/Doctrine%20ORM-^2.19%20|%20^3.0-59666C?style=flat-square)](https://www.doctrine-project.org/projects/orm.html)
[![License](https://img.shields.io/packagist/l/debesha/doctrine-hydration-profiler-bundle.svg?style=flat-square)](LICENSE)
[![Downloads](https://img.shields.io/packagist/dt/debesha/doctrine-hydration-profiler-bundle.svg?style=flat-square)](https://packagist.org/packages/debesha/doctrine-hydration-profiler-bundle/stats)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44B3E6?style=flat-square)](https://phpstan.org/)
[![Code Style](https://img.shields.io/badge/Code%20Style-PHP%20CS%20Fixer-1C7ED6?style=flat-square)](https://cs.symfony.com/)

Bundle to get information about doctrine hydration performance

## Purpose

Adds a section to web profile which lists all doctrine hydrations performed during generation of response.

## Installation

```sh
composer require "debesha/doctrine-hydration-profiler-bundle"
```

Then run `php composer.phar update `

Next step is to register the bundle in AppKernel (`app/AppKernel.php`)

```php
if (in_array($this->getEnvironment(), array('dev', 'test'))) {
    [...]
    $bundles[] = new Debesha\DoctrineProfileExtraBundle\DebeshaDoctrineProfileExtraBundle();
}
```

**Attention! The bundle MUST be included AFTER DoctrineBundle.**

That's it.

## Screenshots

Your profile gets a new section where you may get an information about how fast was hydrations made and
how many entities was hydrated.

![Screenshot](http://i.imgur.com/GsvkIIN.png)

![Screenshot](http://i.imgur.com/pkLzlc8.png)
