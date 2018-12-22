<?php
namespace Triadev\Es\Dsl\Dsl;

use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;
use Triadev\Es\Dsl\Dsl\Aggregation\Bucketing;
use Triadev\Es\Dsl\Dsl\Aggregation\Metric;
use Triadev\Es\Dsl\Dsl\Aggregation\Pipeline;

class Aggregation
{
    /** @var \ONGR\ElasticsearchDSL\Search */
    private $search;
    
    /**
     * Aggregation constructor.
     * @param \ONGR\ElasticsearchDSL\Search $search
     */
    public function __construct(\ONGR\ElasticsearchDSL\Search $search)
    {
        $this->search = $search;
    }
    
    /**
     * Bucketing
     *
     * @param \Closure $bucketing
     * @return Aggregation
     */
    public function bucketing(\Closure $bucketing) : Aggregation
    {
        $bucketingBuilder = new Bucketing();
        $bucketing($bucketingBuilder);
        
        $this->append($bucketingBuilder->getAggregations());
        return $this;
    }
    
    /**
     * Metric
     *
     * @param \Closure $metric
     * @return Aggregation
     */
    public function metric(\Closure $metric) : Aggregation
    {
        $metricBuilder = new Metric();
        $metric($metricBuilder);
        
        $this->append($metricBuilder->getAggregations());
        return $this;
    }
    
    /**
     * Pipeline
     *
     * @param \Closure $pipeline
     * @return Aggregation
     */
    public function pipeline(\Closure $pipeline) : Aggregation
    {
        $pipelineBuilder = new Pipeline();
        $pipeline($pipelineBuilder);
        
        $this->append($pipelineBuilder->getAggregations());
        return $this;
    }
    
    /**
     * @param AbstractAggregation[] $aggs
     * @return Aggregation
     */
    private function append(array $aggs) : Aggregation
    {
        foreach ($aggs as $agg) {
            if ($agg instanceof AbstractAggregation) {
                $this->search->addAggregation($agg);
            }
        }
        
        return $this;
    }
}
