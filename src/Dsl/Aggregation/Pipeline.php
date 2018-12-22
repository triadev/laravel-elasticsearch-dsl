<?php
namespace Triadev\Es\Dsl\Dsl\Aggregation;

use ONGR\ElasticsearchDSL\Aggregation\Pipeline\BucketScriptAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\BucketSelectorAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\CumulativeSumAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\DerivativeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\ExtendedStatsBucketAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\PercentilesBucketAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\AvgBucketAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\MaxBucketAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\MinBucketAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\SerialDifferencingAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\StatsBucketAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\SumBucketAggregation;

class Pipeline extends Aggs
{
    /**
     * Avg
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function avg(string $name, string $bucketsPath): Pipeline
    {
        $this->addAggregation(new AvgBucketAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Max
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function max(string $name, string $bucketsPath): Pipeline
    {
        $this->addAggregation(new MaxBucketAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Min
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function min(string $name, string $bucketsPath): Pipeline
    {
        $this->addAggregation(new MinBucketAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Percentiles
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function percentiles(string $name, string $bucketsPath): Pipeline
    {
        $this->addAggregation(new PercentilesBucketAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Stats
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function stats(string $name, string $bucketsPath): Pipeline
    {
        $this->addAggregation(new StatsBucketAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Sum
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function sum(string $name, string $bucketsPath): Pipeline
    {
        $this->addAggregation(new SumBucketAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Serial differencing
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function serialDifferencing(string $name, string $bucketsPath) : Pipeline
    {
        $this->addAggregation(new SerialDifferencingAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Extended stats
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function extendedStats(string $name, string $bucketsPath) : Pipeline
    {
        $this->addAggregation(new ExtendedStatsBucketAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Derivative
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function derivative(string $name, string $bucketsPath) : Pipeline
    {
        $this->addAggregation(new DerivativeAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Cumulative sum
     *
     * @param string $name
     * @param string $bucketsPath
     * @return Pipeline
     */
    public function cumulativeSum(string $name, string $bucketsPath) : Pipeline
    {
        $this->addAggregation(new CumulativeSumAggregation($name, $bucketsPath));
        return $this;
    }
    
    /**
     * Bucket selector
     *
     * @param string $name
     * @param string $bucketsPath
     * @param string $script
     * @return Pipeline
     */
    public function bucketSelector(
        string $name,
        /** @scrutinizer ignore-type */ string $bucketsPath,
        string $script
    ) : Pipeline {
        $this->addAggregation(new BucketSelectorAggregation($name, $bucketsPath, $script));
        return $this;
    }
    
    /**
     * Bucket
     *
     * @param string $name
     * @param string $bucketsPath
     * @param string $script
     * @return Pipeline
     */
    public function bucketScript(
        string $name,
        /** @scrutinizer ignore-type */ string $bucketsPath,
        string $script
    ) : Pipeline {
        $this->addAggregation(new BucketScriptAggregation($name, $bucketsPath, $script));
        return $this;
    }
}
