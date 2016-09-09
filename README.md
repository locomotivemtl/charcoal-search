
Charcoal Search
===============

This module has all the tools and utilities to create a quick search engine for a Charcoal-based projects.

It features a very customizable search engines / search configuration that allows to search in SQL database tables, Charcoal Models or do custom search.

# Example usage

```php
use \Charcoal\Search\SearchRunner;

use \Foo\Bar\CustomObject;

$searchRunner = new SearchRunner([
	'search_config' => [
		'ident' 		=> 'my-custom-search',
		'objects'		=> [
			'foo' 	=> [
				'search_type' 	=> 'custom',
				'callback' 		=> function($keyword) {
						// Do search here, return array of objects.
						return [];
				}
			],
			'bar'   => [
				'search_type' 	=> 'model',
				'obj_type'			=> new CustomObject()
			]
		]
	],
	'model_factory'		=> $modelFactory,
	'logger'			=> $logger
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


# Search config

The search config object contains the search ident as well as the various searches to run on objects.

| Ident     | Type | Description |
| --------- | ---- | ----------- |
| **ident** | `string` | 
| **objects** | `array` | The various searches to perform.

# Search types

Available search types, which are defined in the search config's objects:

- `custom`
- `table` (todo)
- `model` (todo)

## Custom search

The `custom` search defines a callback function. This can either be a callable (a method or an object with an `__invoke` method) or a string (which will attempt to call the function of matching name on the Search Runner object).

The callback method must have the following signature:
`array callback(string $keyword);` 

### Custom options

| Ident        | Type | Description |
| ------------ | ---- | ----------- |
| **callback** | `callback` | 

## Table search

The `table` search is still **todo**.

## Model search

The `model` search is still **todo**.
