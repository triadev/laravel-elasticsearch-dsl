<?php
namespace Tests\Integration;

use Tests\TestCase;
use Triadev\Es\Dsl\Facade\ElasticDsl;

abstract class IntegrationTestCase extends TestCase
{
    const ELASTIC_INDEX = 'phpunit';
    const ELASTIC_TYPE = 'test';
    
    /**
     * Refresh elasticsearch mappings
     */
    public function refreshElasticsearchMappings()
    {
        $this->deleteElasticsearchMappings();
        
        ElasticDsl::getEsClient()->indices()->create([
            'index' => self::ELASTIC_INDEX
        ]);
    }
    
    /**
     * Delete elasticsearch mappings
     */
    public function deleteElasticsearchMappings()
    {
        if (ElasticDsl::getEsClient()->indices()->exists(['index' => self::ELASTIC_INDEX])) {
            ElasticDsl::getEsClient()->indices()->delete(['index' => self::ELASTIC_INDEX]);
        }
    }
}
