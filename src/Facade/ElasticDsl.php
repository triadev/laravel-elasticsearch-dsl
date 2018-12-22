<?php
namespace Triadev\Es\Dsl\Facade;

use Elasticsearch\Client;
use Illuminate\Support\Facades\Facade;
use Triadev\Es\Dsl\Contract\ElasticsearchDslContract;
use ONGR\ElasticsearchDSL\Search as OngrSearch;
use Triadev\Es\Dsl\Dsl\Search;
use Triadev\Es\Dsl\Dsl\Suggestion;

/**
 * Class ElasticDsl
 * @package Triadev\Es\Dsl\Facade
 *
 * @method static Client getEsClient()
 * @method static Search search(?OngrSearch $search = null)
 * @method static Suggestion suggest(?OngrSearch $search = null)
 */
class ElasticDsl extends Facade
{
    /**
     * @return ElasticsearchDslContract
     */
    protected static function getFacadeAccessor()
    {
        return app(ElasticsearchDslContract::class);
    }
}
