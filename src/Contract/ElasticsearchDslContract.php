<?php
namespace Triadev\Es\Dsl\Contract;

use Elasticsearch\Client;
use Triadev\Es\Dsl\Dsl\Search;
use ONGR\ElasticsearchDSL\Search as OngrSearch;
use Triadev\Es\Dsl\Dsl\Suggestion;

interface ElasticsearchDslContract
{
    /**
     * Get es client
     *
     * @return Client
     */
    public function getEsClient() : Client;
    
    /**
     * Search
     *
     * @param OngrSearch|null $search
     * @return Search
     */
    public function search(?OngrSearch $search = null) : Search;
    
    /**
     * Suggestion
     *
     * @param OngrSearch|null $search
     * @return Suggestion
     */
    public function suggest(?OngrSearch $search = null) : Suggestion;
}
