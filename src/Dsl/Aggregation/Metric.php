<?php
namespace Triadev\Es\Dsl\Dsl\Aggregation;

use ONGR\ElasticsearchDSL\Aggregation\Metric\AvgAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\CardinalityAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\ExtendedStatsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\GeoBoundsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\GeoCentroidAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\MaxAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\MinAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\StatsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\SumAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\TopHitsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\ValueCountAggregation;
use ONGR\ElasticsearchDSL\BuilderInterface;

class Metric extends Aggs
{
    /**
     * Avg
     *
     * @param string $name
     * @param string|null $field
     * @param string|null $script
     * @return Metric
     */
    public function avg(string $name, ?string $field = null, ?string $script = null) : Metric
    {
        $this->addAggregation(new AvgAggregation($name, $field, $script));
        return $this;
    }
    
    /**
     * Cardinality
     *
     * @param string $name
     * @param string $field
     * @return Metric
     */
    public function cardinality(string $name, string $field) : Metric
    {
        $cardinality = new CardinalityAggregation($name);
        $cardinality->setField($field);
        
        $this->addAggregation($cardinality);
        return $this;
    }
    
    /**
     * Extended stats
     *
     * @param string $name
     * @param string $field
     * @param int|null $sigma
     * @param string|null $script
     * @return Metric
     */
    public function extendedStats(string $name, string $field, ?int $sigma = null, ?string $script = null) : Metric
    {
        $this->addAggregation(new ExtendedStatsAggregation($name, $field, $sigma, $script));
        return $this;
    }
    
    /**
     * Geo bounds
     *
     * @param string $name
     * @param string|null $field
     * @param bool $wrapLongitude
     * @return Metric
     */
    public function geoBounds(string $name, ?string $field = null, bool $wrapLongitude = true) : Metric
    {
        $this->addAggregation(new GeoBoundsAggregation($name, $field, $wrapLongitude));
        return $this;
    }
    
    /**
     * Geo centroid
     *
     * @param string $name
     * @param string|null $field
     * @return Metric
     */
    public function geoCentroid(string $name, ?string $field = null) : Metric
    {
        $this->addAggregation(new GeoCentroidAggregation($name, $field));
        return $this;
    }
    
    /**
     * Max
     *
     * @param string $name
     * @param string|null $field
     * @param string|null $script
     * @return Metric
     */
    public function max(string $name, ?string $field = null, ?string $script = null) : Metric
    {
        $this->addAggregation(new MaxAggregation($name, $field, $script));
        return $this;
    }
    
    /**
     * Min
     *
     * @param string $name
     * @param string|null $field
     * @param string|null $script
     * @return Metric
     */
    public function min(string $name, ?string $field = null, ?string $script = null): Metric
    {
        $this->addAggregation(new MinAggregation($name, $field, $script));
        return $this;
    }
    
    /**
     * Stats
     *
     * @param string $name
     * @param string|null $field
     * @param string|null $script
     * @return Metric
     */
    public function stats(string $name, ?string $field = null, ?string $script = null): Metric
    {
        $this->addAggregation(new StatsAggregation($name, $field, $script));
        return $this;
    }
    
    /**
     * Sum
     *
     * @param string $name
     * @param string|null $field
     * @param string|null $script
     * @return Metric
     */
    public function sum(string $name, ?string $field = null, ?string $script = null): Metric
    {
        $this->addAggregation(new SumAggregation($name, $field, $script));
        return $this;
    }
    
    /**
     * Top hits
     *
     * @param string $name
     * @param int|null $size
     * @param int|null $from
     * @param BuilderInterface|null $sort
     * @return Metric
     */
    public function topHits(string $name, ?int $size = null, ?int $from = null, ?BuilderInterface $sort = null) : Metric
    {
        $this->addAggregation(new TopHitsAggregation($name, $size, $from, $sort));
        return $this;
    }
    
    /**
     * Value count
     *
     * @param string $name
     * @param string|null $field
     * @param string|null $script
     * @return Metric
     */
    public function valueCount(string $name, ?string $field = null, ?string $script = null): Metric
    {
        $this->addAggregation(new ValueCountAggregation($name, $field, $script));
        return $this;
    }
}
