<?php
namespace Tests\Integration\Dsl;

use Tests\Integration\IntegrationTestCase;
use Triadev\Es\Dsl\Facade\ElasticDsl;

class SearchTest extends IntegrationTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    
        $this->refreshElasticsearchMappings();
    
        ElasticDsl::getEsClient()->indices()->putMapping([
            'index' => 'phpunit',
            'type' => 'test',
            'body' => [
                'properties' => [
                    'test' => [
                        'type' => 'keyword'
                    ]
                ]
            ]
        ]);
    }
    
    private function buildPayload(array $payload = []) : array
    {
        return array_merge([
            'index' => self::ELASTIC_INDEX,
            'type' => self::ELASTIC_TYPE
        ], $payload);
    }
    
    private function createTestDocument()
    {
        ElasticDsl::getEsClient()->index($this->buildPayload([
            'id' => 1,
            'body' => [
                'test' => 'phpunit'
            ]
        ]));
        
        ElasticDsl::getEsClient()->indices()->refresh();
    }
    
    /**
     * @test
     */
    public function it_returns_an_elasticsearch_search_result_object()
    {
        $this->createTestDocument();
        
        $result = ElasticDsl::search()
            ->esIndex(self::ELASTIC_INDEX)
            ->esType(self::ELASTIC_TYPE)
            ->termLevel()
                ->term('test', 'phpunit')
            ->get();
        
        $this->assertIsInt($result->getTook());
        $this->assertIsBool($result->isTimedOut());
        $this->assertIsFloat($result->getMaxScore());
        
        $this->assertEquals(5, $result->getShards()['total']);
        $this->assertEquals(5, $result->getShards()['successful']);
        $this->assertEquals(1, $result->getTotalHits());
        
        $this->assertNotEmpty($result->getHits());
        
        foreach ($result->getHits() as $hit) {
            $this->assertTrue(is_array($hit));
        }
    }
}
