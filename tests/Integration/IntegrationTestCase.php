<?php
namespace Tests\Integration;

use Mockery\MockInterface;
use Tests\TestCase;
use Triadev\Es\Dsl\Facade\ElasticDsl;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;

abstract class IntegrationTestCase extends TestCase
{
    const ELASTIC_INDEX = 'phpunit';
    const ELASTIC_TYPE = 'test';
    
    /** @var MockInterface */
    protected $mockPrometheusExporter;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    
        $this->mockPrometheusExporter = \Mockery::mock(PrometheusExporterContract::class);
        app()->instance(PrometheusExporterContract::class, $this->mockPrometheusExporter);
    }
    
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        
        $app['config']->set('laravel-elasticsearch-dsl.metrics.enabled', true);
        $app['config']->set('laravel-elasticsearch-dsl.metrics.buckets', [
            'search' => [
                5, 10, 25, 50, 75, 100, 250, 500, 750, 1000, 2500, 5000, 7500, 10000, 15000, 25000, 50000
            ],
            'suggest' => [
                5, 10, 25, 50, 75, 100, 250, 500, 750, 1000, 2500, 5000, 7500, 10000, 15000, 25000, 50000
            ]
        ]);
    }
    
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
