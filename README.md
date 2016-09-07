
Charcoal Search
===============

This module has all the tools and utilities to create a quick search engine for a Charcoal-based projects.

It features a very customizable search engines / search configuration that allows to search in SQL database tables, Charcoal Models or do custom search.

# Example (planned) usage

```php
use \Charcoal\Search\SearchRunner;

use \Foo\Bar\CustomObject;

$searchRunner = new SearchRunner([
	'search_config' => [
		'ident' 		=> 'bim',
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
				'obj_type'			=> CustomObject::class
			]
		]
	],
	'model_factory'		=> $modelFactory,
	'logger'			=> $logger
]);

// The results are an array like `['foo'=>[...], 'bar'=>[...]]`
$results = $searchRunner->search($keyword);

// Access log
$log = $search->searchLog();

// Differed access to results
$results = $search->results();
```

## Search types

Available search types:

The `custom` search defines a callback function. This can either be a callable or a string (which will attempt to call the function of matching name on the Search Runner object).


The `table` search is still **todo**.

The `model` search is still **todo**.
