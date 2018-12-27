<?php
namespace Tests\Integration\Dsl;

use Tests\Integration\IntegrationTestCase;
use Triadev\Es\Dsl\Dsl\Aggregation;
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
                    ],
                    'counter' => [
                        'type' => 'integer'
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
                'test' => 'phpunit',
                'counter' => 5
            ]
        ]));
        
        ElasticDsl::getEsClient()->indices()->refresh();
    }
    
    private function buildClosureForSetHistogramArgs(string $handler) : \Closure
    {
        return function ($name, $help, $value, $namespace, $labelKeys, $labelValues, $buckets) use ($handler) {
            return $name == 'query_duration_milliseconds' &&
                $help == 'Get the query duration.' &&
                is_numeric($value) &&
                $namespace == 'triadev_laravel_elasticsearch_dsl' &&
                $labelKeys == ['handler'] &&
                $labelValues == [$handler] &&
                is_array($buckets);
        };
    }
    
    /**
     * @test
     */
    public function it_returns_an_elasticsearch_search_result_object()
    {
        $this->mockPrometheusExporter
            ->shouldReceive('setHistogram')
            ->withArgs($this->buildClosureForSetHistogramArgs('search'))
            ->times(1);
        
        $this->createTestDocument();
        
        $result = ElasticDsl::search()
            ->esIndex(self::ELASTIC_INDEX)
            ->esType(self::ELASTIC_TYPE)
            ->termLevel()
                ->term('test', 'phpunit')
            ->aggregation(function (Aggregation $aggregation) {
                $aggregation->metric(function (Aggregation\Metric $metric) {
                    $metric->max('MAX', 'counter');
                });
            })
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
        
        $this->assertEquals([
            'MAX' => [
                'value' => 5.0
            ]
        ], $result->getAggregation());
    }
}
