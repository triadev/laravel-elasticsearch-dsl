# laravel-elasticsearch-dsl
A service provider for laravel with a fluent elasticsearch query and aggregation dsl.

[![Software license][ico-license]](LICENSE)
[![Travis][ico-travis]][link-travis]
[![Coveralls](https://coveralls.io/repos/github/triadev/laravel-elasticsearch-dsl/badge.svg?branch=master)](https://coveralls.io/github/triadev/laravel-elasticsearch-dsl?branch=master)
[![CodeCov](https://codecov.io/gh/triadev/laravel-elasticsearch-dsl/branch/master/graph/badge.svg)](https://codecov.io/gh/triadev/laravel-elasticsearch-dsl)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/triadev/laravel-elasticsearch-dsl/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/triadev/laravel-elasticsearch-dsl/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/triadev/laravel-elasticsearch-dsl/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/triadev/laravel-elasticsearch-dsl/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/triadev/laravel-elasticsearch-dsl/badges/build.png?b=master)](https://scrutinizer-ci.com/g/triadev/laravel-elasticsearch-dsl/build-status/master)

[![Latest stable][ico-version-stable]][link-packagist]
[![Latest development][ico-version-dev]][link-packagist]
[![Monthly installs][ico-downloads-monthly]][link-downloads]

## Supported laravel versions
[![Laravel 5.5][icon-l55]][link-laravel]
[![Laravel 5.6][icon-l56]][link-laravel]
[![Laravel 5.7][icon-l57]][link-laravel]

## Supported elasticsearch versions
[![Elasticsearch 6.0][icon-e60]][link-elasticsearch]
[![Elasticsearch 6.1][icon-e61]][link-elasticsearch]
[![Elasticsearch 6.2][icon-e62]][link-elasticsearch]
[![Elasticsearch 6.3][icon-e63]][link-elasticsearch]
[![Elasticsearch 6.4][icon-e64]][link-elasticsearch]

## Installation

### Composer
> composer require triadev/laravel-elasticsearch-dsl

### Application
The package is registered through the package discovery of laravel and Composer.
>https://laravel.com/docs/5.7/packages

Once installed you can now publish your config file and set your correct configuration for using the package.
```php
php artisan vendor:publish --provider="Triadev\Es\Dsl\Provider\ServiceProvider" --tag="config"
```

This will create a file ```config/laravel-elasticsearch-dsl.php```.

## Usage

Dieses Paket bietet eine DSL für Elasticsearch.

Jede Abfrage gibt ein Object zurück, welches das Suchergebnis beinhaltet.
>Triadev\Es\Dsl\Model\SearchResult
```php
int: time needed to execute the query
$result->took();

bool
$result->timedOut();

int: number of matched documents
$result->totalHits();

Illuminate\Support\Collection: collection of searchable eloquent models
$result->hits();
```

## Bool
Bei jeder Abfrage, die auf Bool basiert, kann der Bool-Status verändert werden.
>Default bool state: must
```php
ElasticDsl::search()->termLevel()
    ->must()
        ->term('FIELD', 'VALUE')
    ->mustNot()
        ->term('FIELD', 'VALUE')
    ->should()
        ->term('FIELD', 'VALUE')
    ->filter()
        ->term('FIELD', 'VALUE')
})->get()
```

### Nested bool query
Eine verschachtelte Query wird über ```bool(\Closure $closure)``` realisiert.
```php
ElasticDsl::search()
    ->termLevel()
        ->term('FIELD', 'VALUE')
        ->bool(function (Search $search) {
            $search->termLevel()
                ->term('FIELD', 'VALUE')
                ->bool(function (Search $search) {
                    $search->fulltext()
                        ->match('FIELD1', 'QUERY1')
                        ->matchPhrase('FIELD2', 'QUERY2');
                });
            })
        ->prefix('FIELD', 'VALUE')
    ->get();

--------------------------------------------------
[
    "query" => [
        "bool" => [
            "must" => [
                [
                    "term" => [
                        "FIELD" => "VALUE"
                    ]
                ],
                [
                    "bool" => [
                        "must" => [
                            [...],
                            [...]
                        ]
                    ]
                ],
                [
                    "prefix" => [
                        "FIELD" => [
                            "value" => "VALUE"
                        ]
                    ]
                ]
            ]
        ]
    ]
]
```

## TermLevel
>matchAll, exists, fuzzy, ids, prefix, range, regexp, term, terms, type, wildcard
```php
ElasticDsl::search()->termLevel()->filter()->term('FIELD', 'VALUE')->get();
```

## Fulltext
>match, matchPhrase, matchPhrasePrefix, multiMatch, queryString, simpleQueryString, commonTerms
```php
ElasticDsl::search()->fulltext()->must()->match('FIELD', 'QUERY')->get();
```

## Geo
>geoBoundingBox, geoDistance, geoPolygon, geoShape
```php
ElasticDsl::search()->geo()->filter()->geoDistance('FIELD','10km', new Location(1, 2))->get();
```

## Compound
>functionScore, constantScore, boosting, disMax
```php
ElasticDsl::search()->compound()->functionScore(
    function (Search $search) {
        $search->termLevel()->term('FIELD1', 'VALUE1');
    },
    function (FunctionScore $functionScore) {
        $functionScore->simple([]);
    }
)->get();
```

## Joining
>nested, hasChild, hasParent
```php
ElasticDsl::search()->joining()->nested('PATH', function (Search $search) {
   $search->termLevel()->filter()->term('FIELD', 'VALUE');
})->get();
```

## Specialized
>moreLikeThis
```php
ElasticDsl::search()->specialized()->moreLikeThis('LIKE')->toDsl();
```

## InnerHit
>nestedInnerHit, parentInnerHit
```php
ElasticDsl::search()->nestedInnerHit('NAME', 'PATH', function (Search $search) {
    $search->termLevel()->term('FIELD', 'VALUE');
})->get();
```

### Individual index and type
To set an individual index or type per query you have two overwrite methods.
```php
ElasticDsl::search()
    ->overwriteIndex('INDEX')
    ->overwriteType('TYPE')
    ->termLevel()
        ->matchAll()
    ->get();
```

## Aggregation
> Bucketing, Metric, Pipeline
```php
ElasticDsl::search()->aggregation(function (Aggregation $aggregation) {
    $aggregation->metric(function (Aggregation\Metric $metric) {
        ...
    });
})->get();

ElasticDsl::search()->aggregation(function (Aggregation $aggregation) {
    $aggregation->bucketing(function (Aggregation\Bucketing $metric) {
        ...
    });
})->get();

ElasticDsl::search()->aggregation(function (Aggregation $aggregation) {
    $aggregation->pipeline(function (Aggregation\Pipeline $metric) {
        ...
    });
})->get();
```

## Suggestions
>term, phrase, completion
```php
ElasticDsl::suggest()->term('NAME', 'TEXT', 'FIELD')->get();
```

## Reporting Issues
If you do find an issue, please feel free to report it with GitHub's bug tracker for this project.

Alternatively, fork the project and make a pull request. :)

## Testing
1. docker-compose -f docker-compose.yml up
2. composer test

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits
- [Christopher Lorke][link-author]
- [All Contributors][link-contributors]

## Other

### Project related links
- [Wiki](https://github.com/triadev/laravel-elasticsearch-dsl/wiki)
- [Issue tracker](https://github.com/triadev/laravel-elasticsearch-dsl/issues)

### License
The code for laravel-elasticsearch-dsl is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).

[ico-license]: https://img.shields.io/github/license/triadev/laravel-elasticsearch-dsl.svg?style=flat-square
[ico-version-stable]: https://img.shields.io/packagist/v/triadev/laravel-elasticsearch-dsl.svg?style=flat-square
[ico-version-dev]: https://img.shields.io/packagist/vpre/triadev/laravel-elasticsearch-dsl.svg?style=flat-square
[ico-downloads-monthly]: https://img.shields.io/packagist/dm/laravel-elasticsearch-dsl/laravel-elasticsearch-dsl.svg?style=flat-square
[ico-travis]: https://travis-ci.org/triadev/laravel-elasticsearch-dsl.svg?branch=master

[link-packagist]: https://packagist.org/packages/triadev/laravel-elasticsearch-dsl
[link-downloads]: https://packagist.org/packages/triadev/laravel-elasticsearch-dsl/stats
[link-travis]: https://travis-ci.org/triadev/laravel-elasticsearch-dsl

[icon-l55]: https://img.shields.io/badge/Laravel-5.5-brightgreen.svg?style=flat-square
[icon-l56]: https://img.shields.io/badge/Laravel-5.6-brightgreen.svg?style=flat-square
[icon-l57]: https://img.shields.io/badge/Laravel-5.7-brightgreen.svg?style=flat-square

[icon-e60]: https://img.shields.io/badge/Elasticsearch-6.0-brightgreen.svg?style=flat-square
[icon-e61]: https://img.shields.io/badge/Elasticsearch-6.1-brightgreen.svg?style=flat-square
[icon-e62]: https://img.shields.io/badge/Elasticsearch-6.2-brightgreen.svg?style=flat-square
[icon-e63]: https://img.shields.io/badge/Elasticsearch-6.3-brightgreen.svg?style=flat-square
[icon-e64]: https://img.shields.io/badge/Elasticsearch-6.4-brightgreen.svg?style=flat-square

[link-laravel]: https://laravel.com
[link-elasticsearch]: https://www.elastic.co/
[link-author]: https://github.com/triadev
[link-contributors]: ../../contributors