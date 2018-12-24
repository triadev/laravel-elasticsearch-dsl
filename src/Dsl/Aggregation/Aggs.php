<?php
namespace Triadev\Es\Dsl\Dsl\Aggregation;

use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;

abstract class Aggs
{
    /**
     * Aggregations
     *
     * @var array
     */
    private $aggregations = [];
    
    /**
     * Add aggregation
     *
     * @param AbstractAggregation $agg
     */
    protected function addAggregation(AbstractAggregation $agg)
    {
        $this->aggregations[] = $agg;
    }
    
    /**
     * Get aggregations
     *
     * @return array
     */
    public function getAggregations() : array
    {
        return $this->aggregations;
    }
}
