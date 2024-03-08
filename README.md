# DoctrineProfileExtraBundle

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
