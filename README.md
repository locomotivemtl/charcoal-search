
Charcoal Search
===============

This module has all the tools and utilities to create a quick search engine for a Charcoal-based projects.

It features a very customizable search engines / search configuration that allows to search in SQL database tables, Charcoal Models or do custom search.

# Table of content

- [How to install](#how-to-install)
    -   [Dependencies](#dependencies)
- [Example usage](#example-usage)
- [Constructor options](#constructor-options)
- [Search config](#search-config)
- [Search types](#search-types)
    - [Custom search](#custom-search)
        - Custom options 
    - [Model search](#model-search)
    - [Table search](#table-search)
- [Search log](#search-log)
- [Charcoal-admin integration](#charcioal-admin-integration)
- [Development](#development)
    - [Development dependencies](#development-dependencies)
    - [Coding Style](#coding-style)
    - [Authors](#authors)
    - [Changelog](#changelog)

# How to install

The preferred (and only supported) way of installing _charcoal-search_ is with **composer**:

```shell
★ composer require locomotivemtl/charcoal-search
```

## Dependencies

-   [`PHP 5.5+`](http://php.net)
-   [`locomotivemtl/charcoal-core`](https://github.com/locomotivemtl/charcoal-core)
-   [`locomotivemtl/charcoal-base`](https://github.com/locomotivemtl/charcoal-base)

# Example usage

```php
use \Charcoal\Search\SearchRunner;

use \Foo\Bar\CustomObject;

$searchRunner = new SearchRunner([
    'search_config' => [
        'ident'    => 'my-custom-search',
        'searches' => [
            'foo'  => new CustomSearch([
                'logger'   => $logger
                'callback' => function($keyword) {

                }
            ])
        ]
    ],
    'model_factory' => $modelFactory,
    'logger'        => $logger
]);

// The results are an array like `['foo'=>[...], 'bar'=>[...]]`
$results = $searchRunner->search($keyword);

// Access log
$log = $searchRunner->searchLog();

// Differed access to results
$results = $searchRunner->results();
```

# Constructor options

The `SearchRunner` is instanciated with a single parameter, which contains the constructor options and class dependencies:

| Ident | Type | Description |
| ----- | ---- | ----------- |
| **logger** | `\Psr\Log\LoggerInterface` | A PSR-3 logger. |
| **model_factory** | `\Charcoal\Factory\FactoryInterface` | A factory to create objects (and logs). |
| **search_config** | `array` | A [search config](#search-config) object


## Search config

The search config object contains the search ident as well as the various searches to run on objects.

| Ident     | Type | Description |
| --------- | ---- | ----------- |
| **ident** | `string` | 
| **searches** | `array` | The various searches to perform.

# Search types

Available search types, which are defined in the search config's **searches**:

-   `custom`
-   `table` (todo)
-   `model` (todo)

## Custom search

The `custom` search defines a callback function. This can either be a callable (a method or an object with an `__invoke` method) or a string (which will attempt to call the function of matching name on the Search Runner object).

The callback method must have the following signature:
`array callback(string $keyword);` 

### Custom options

| Ident        | Type | Description |
| ------------ | ---- | ----------- |
| **callback** | `callback` | Optional callback to defer searching to. |

## Table search

The `table` search is still **todo**.

## Model search

The `model` search is still **todo**.

# Search log

Every search is automatically logged to (SQL) storage.

# Charcoal-admin integration

The search-log object comes by default with full metadata to display in charcoal-admin. Visit at \[\[your-site.com\]\]/admin/object/collection?obj-type=charcoal/search/search-log.

There are 2 widgets also available to visualize logs:

- `charcoal/admin/widget/search/no-results-search`
- `charcoal/admin/widget/search/top-search`

# Development

To install the development environment:

```shell
★ composer install --prefer-source
```

To run the scripts (phplint, phpcs and phpunit):

```shell
★ composer test
```

## Development dependencies

- `phpunit/phpunit`
- `squizlabs/php_codesniffer`
- `satooshi/php-coveralls`

## Continuous Integration

| Service | Badge | Description |
| ------- | ----- | ----------- |
| [Travis](https://travis-ci.org/locomotivemtl/charcoal-search) | [![Build Status](https://travis-ci.org/locomotivemtl/charcoal-search.svg?branch=master)](https://travis-ci.org/locomotivemtl/charcoal-search) | Runs code sniff check and unit tests. Auto-generates API documentation. |
| [Scrutinizer](https://scrutinizer-ci.com/g/locomotivemtl/charcoal-search/) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/locomotivemtl/charcoal-search/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/locomotivemtl/charcoal-search/?branch=master) | Code quality checker. Also validates API documentation quality. |
| [Coveralls](https://coveralls.io/github/locomotivemtl/charcoal-search) | [![Coverage Status](https://coveralls.io/repos/github/locomotivemtl/charcoal-search/badge.svg?branch=master)](https://coveralls.io/github/locomotivemtl/charcoal-search?branch=master) | Unit Tests code coverage. |
| [Sensiolabs](https://insight.sensiolabs.com/projects/d7fe2c2a-5624-4e4c-9de2-4bc4c5f4c965) | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/d7fe2c2a-5624-4e4c-9de2-4bc4c5f4c965/mini.png)](https://insight.sensiolabs.com/projects/d7fe2c2a-5624-4e4c-9de2-4bc4c5f4c965) | Another code quality checker, focused on PHP. |

## Coding Style

All Charcoal modules follow the same coding style and `charcoal-search` is no exception. For PHP:

- [_PSR-1_](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
- [_PSR-2_](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
- [_PSR-4_](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md), autoloading is therefore provided by _Composer_
- [_phpDocumentor_](http://phpdoc.org/)
- Read the [phpcs.xml](phpcs.xml) file for all the details on code style.

> Coding style validation / enforcement can be performed with `composer phpcs`. An auto-fixer is also available with `composer phpcbf`.

## Authors

- Mathieu Ducharme <mat@locomotive.ca>

## Changelog

- Unreleased

**The MIT License (MIT)**

_Copyright © 2016 Locomotive inc._

> See [Authors](#authors).

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
