<?php
namespace Triadev\Es\Dsl\Dsl;

use Illuminate\Support\Facades\Log;
use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Search as OngrSearch;
use Prometheus\Exception\MetricsRegistrationException;
use Triadev\Es\Dsl\Dsl\Query\Compound;
use Triadev\Es\Dsl\Dsl\Query\Fulltext;
use Triadev\Es\Dsl\Dsl\Query\Geo;
use Triadev\Es\Dsl\Dsl\Query\InnerHit;
use Triadev\Es\Dsl\Dsl\Query\Joining;
use Triadev\Es\Dsl\Dsl\Query\Specialized;
use Triadev\Es\Dsl\Dsl\Query\TermLevel;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;

abstract class AbstractSearch
{
    /** @var OngrSearch */
    protected $search;
    
    /** @var string|null */
    private $esIndex;
    
    /** @var string|null */
    private $esType;
    
    /**
     * AbstractDsl constructor.
     * @param OngrSearch|null $search
     * @param string|null $esIndex
     * @param string|null $esType
     */
    public function __construct(
        ?OngrSearch $search = null,
        ?string $esIndex = null,
        ?string $esType = null
    ) {
        $this->search = $search ?: new OngrSearch();
        
        $this->esIndex = $esIndex;
        $this->esType = $esType;
    }
    
    /**
     * Overwrite the default elasticsearch index
     *
     * @param string $index
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function esIndex(string $index): AbstractSearch
    {
        $this->esIndex = $index;
        return $this;
    }
    
    /**
     * Get elasticsearch index
     *
     * @return string
     */
    public function getEsIndex(): string
    {
        return $this->esIndex ?: config('laravel-elasticsearch-dsl.index');
    }
    
    /**
     * Overwrite the default elasticsearch type
     *
     * @param string $type
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function esType(string $type): AbstractSearch
    {
        $this->esType = $type;
        return $this;
    }
    
    /**
     * Get elasticsearch type
     *
     * @return string|null
     */
    public function getEsType(): ?string
    {
        return $this->esType;
    }
    
    /**
     * Get current search
     *
     * @return OngrSearch
     */
    protected function getCurrentSearch(): OngrSearch
    {
        return $this->search;
    }
    
    /**
     * To dsl
     *
     * @return array
     */
    public function toDsl(): array
    {
        return $this->search->toArray();
    }
    
    /**
     * Get search
     *
     * @return OngrSearch
     */
    public function getSearch(): OngrSearch
    {
        return $this->search;
    }
    
    /**
     * Get query
     *
     * @return BuilderInterface
     */
    public function getQuery(): BuilderInterface
    {
        return $this->search->getQueries();
    }
    
    /**
     * Init metric start time
     *
     * @return float|null
     */
    protected function initMetricStartTime(): ?float
    {
        return config('laravel-elasticsearch-dsl.metrics.enabled', false) ?
            microtime(true) :
            null;
    }
    
    /**
     * Set histogram metric
     *
     * @param float $startTime
     * @param string $handler
     */
    protected function setHistogramMetric(float $startTime, string $handler)
    {
        try {
            $this->getPrometheusExporter()->setHistogram(
                'query_duration_milliseconds',
                'Get the query duration.',
                round((microtime(true) - $startTime) * 1000.0),
                'triadev_laravel_elasticsearch_dsl',
                ['handler'],
                [$handler],
                config(
                    sprintf(
                        'laravel-elasticsearch-dsl.metrics.buckets.%s',
                        $handler
                    )
                )
            );
        } catch (MetricsRegistrationException $e) {
            Log::error('Prometheus exporter metrics registration failed.');
        }
    }
    
    private function getPrometheusExporter() : PrometheusExporterContract
    {
        return app(PrometheusExporterContract::class);
    }
}
