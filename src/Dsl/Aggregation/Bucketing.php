<?php
namespace Triadev\Es\Dsl\Dsl\Aggregation;

use ONGR\ElasticsearchDSL\Aggregation\Bucketing\ChildrenAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\DateHistogramAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\DateRangeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\DiversifiedSamplerAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\FilterAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\FiltersAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\GeoDistanceAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\GeoHashGridAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\HistogramAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\Ipv4RangeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\MissingAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\NestedAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\RangeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\SamplerAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\SignificantTermsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\BuilderInterface;

class Bucketing extends Aggs
{
    /**
     * Children
     *
     * @param string $name
     * @param string $children
     * @param \Closure $bucketing
     * @return Bucketing
     */
    public function children(string $name, string $children, \Closure $bucketing) : Bucketing
    {
        $resultAgg = new ChildrenAggregation($name, $children);
        
        $bucketingBuilder = new self();
        $bucketing($bucketingBuilder);
        
        foreach ($bucketingBuilder->getAggregations() as $agg) {
            $resultAgg->addAggregation($agg);
        }
        
        $this->addAggregation($resultAgg);
        return $this;
    }
    
    /**
     * Date histogram
     *
     * @param string $name
     * @param string $field
     * @param string $interval
     * @param string|null $format
     * @return Bucketing
     *
     * @throws \InvalidArgumentException
     */
    public function dateHistogram(
        string $name,
        string $field,
        string $interval,
        ?string $format = null
    ) : Bucketing {
        $validInterval = [
            'year',
            'quarter',
            'month',
            'week',
            'day',
            'hour',
            'minute',
            'second',
        ];
        
        if (!in_array($interval, $validInterval)) {
            throw new \InvalidArgumentException();
        }
        
        $this->addAggregation(new DateHistogramAggregation($name, $field, $interval, $format));
        return $this;
    }
    
    /**
     * Date range
     *
     * @param string $name
     * @param string $field
     * @param string $format
     * @param array $ranges
     * @return Bucketing
     */
    public function dateRange(
        string $name,
        string $field,
        string $format,
        array $ranges = []
    ) : Bucketing {
        $this->addAggregation(new DateRangeAggregation($name, $field, $format, $ranges));
        return $this;
    }
    
    /**
     * Diversified sampler
     *
     * @param string $name
     * @param string $field
     * @param int|null $shardSize
     * @return Bucketing
     */
    public function diversifiedSampler(string $name, string $field, ?int $shardSize = null) : Bucketing
    {
        $this->addAggregation(new DiversifiedSamplerAggregation($name, $field, $shardSize));
        return $this;
    }
    
    /**
     * Filter
     *
     * @param string $name
     * @param BuilderInterface $aggregation
     * @param BuilderInterface[] $aggregations
     * @return Bucketing
     */
    public function filter(string $name, BuilderInterface $aggregation, array $aggregations) : Bucketing
    {
        $agg = new FilterAggregation($name, $aggregation);
        
        foreach ($aggregations as $a) {
            if ($a instanceof BuilderInterface) {
                $agg->addAggregation($a);
            }
        }
        
        $this->addAggregation($agg);
        return $this;
    }
    
    /**
     * Filters
     *
     * @param string $name
     * @param BuilderInterface[] $filters
     * @param BuilderInterface[] $aggregations
     * @param bool $anonymous
     * @return Bucketing
     */
    public function filters(string $name, array $filters, array $aggregations, bool $anonymous = false) : Bucketing
    {
        $agg = new FiltersAggregation($name, $filters, $anonymous);
    
        foreach ($aggregations as $a) {
            if ($a instanceof BuilderInterface) {
                $agg->addAggregation($a);
            }
        }
    
        $this->addAggregation($agg);
        
        return $this;
    }
    
    /**
     * Geo distance
     *
     * @param string $name
     * @param string $field
     * @param string $origin
     * @param array $ranges
     * @param string|null $unit
     * @param string|null $distanceType
     * @return Bucketing
     */
    public function geoDistance(
        string $name,
        string $field,
        string $origin,
        array $ranges = [],
        string $unit = null,
        string $distanceType = null
    ) : Bucketing {
        $this->addAggregation(
            new GeoDistanceAggregation(
                $name,
                $field,
                $origin,
                $ranges,
                $unit,
                $distanceType
            )
        );
        
        return $this;
    }
    
    /**
     * Geo hash grid
     *
     * @param string $name
     * @param string $field
     * @param int|null $precision
     * @param int|null $size
     * @param int|null $shardSize
     * @return Bucketing
     */
    public function geoHashGrid(
        string $name,
        string $field,
        ?int $precision = null,
        ?int $size = null,
        ?int $shardSize = null
    ) : Bucketing {
        $this->addAggregation(
            new GeoHashGridAggregation(
                $name,
                $field,
                $precision,
                $size,
                $shardSize
            )
        );
        
        return $this;
    }
    
    /**
     * Histogram
     *
     * @param string $name
     * @param string $field
     * @param int $interval
     * @param int|null $minDocCount
     * @param string|null $orderMode
     * @param string $orderDirection
     * @param int|null $extendedBoundsMin
     * @param int|null $extendedBoundsMax
     * @param bool|null $keyed
     * @return Bucketing
     */
    public function histogram(
        string $name,
        string $field,
        int $interval,
        ?int $minDocCount = null,
        ?string $orderMode = null,
        string $orderDirection = HistogramAggregation::DIRECTION_ASC,
        ?int $extendedBoundsMin = null,
        ?int $extendedBoundsMax = null,
        bool $keyed = null
    ) : Bucketing {
        $this->addAggregation(
            new HistogramAggregation(
                $name,
                $field,
                $interval,
                $minDocCount,
                $orderMode,
                $orderDirection,
                $extendedBoundsMin,
                $extendedBoundsMax,
                $keyed
            )
        );
        
        return $this;
    }
    
    /**
     * Ipv4
     *
     * @param string $name
     * @param string $field
     * @param array $ranges
     * @return Bucketing
     */
    public function ipv4Range(string $name, string $field, array $ranges = []) : Bucketing
    {
        $this->addAggregation(
            new Ipv4RangeAggregation(
                $name,
                $field,
                $ranges
            )
        );
        
        return $this;
    }
    
    /**
     * Missing
     *
     * @param string $name
     * @param string $field
     * @return Bucketing
     */
    public function missing(string $name, string $field) : Bucketing
    {
        $this->addAggregation(new MissingAggregation($name, $field));
        return $this;
    }
    
    /**
     * Nested
     *
     * @param string $name
     * @param string $path
     * @param array $aggregations
     * @return Bucketing
     */
    public function nested(string $name, string $path, array $aggregations = []) : Bucketing
    {
        $agg = new NestedAggregation($name, $path);
        
        foreach ($aggregations as $aggregation) {
            if ($aggregation instanceof BuilderInterface) {
                $agg->addAggregation($aggregation);
            }
        }
        
        $this->addAggregation($agg);
        return $this;
    }
    
    /**
     * Range
     *
     * @param string $name
     * @param string $field
     * @param array $ranges
     * @param bool $keyed
     * @return Bucketing
     */
    public function range(string $name, string $field, array $ranges = [], bool $keyed = false) : Bucketing
    {
        $this->addAggregation(new RangeAggregation($name, $field, $ranges, $keyed));
        return $this;
    }
    
    public function reverseNested() : Bucketing
    {
        return $this;
    }
    
    /**
     * Sampler
     *
     * @param string $name
     * @param string $field
     * @param int|null $shardSize
     * @param array $aggregations
     * @return Bucketing
     */
    public function sampler(string $name, string $field, ?int $shardSize = null, array $aggregations = []) : Bucketing
    {
        $agg = new SamplerAggregation($name, $field, $shardSize);
        
        foreach ($aggregations as $aggregation) {
            if ($aggregation instanceof BuilderInterface) {
                $agg->addAggregation($aggregation);
            }
        }
        
        $this->addAggregation($agg);
        return $this;
    }
    
    /**
     * Significant terms
     *
     * @param string $name
     * @param string $field
     * @param string|null $script
     * @return Bucketing
     */
    public function significantTerms(string $name, string $field, ?string $script = null) : Bucketing
    {
        $this->addAggregation(new SignificantTermsAggregation($name, $field, $script));
        return $this;
    }
    
    /**
     * Terms
     *
     * @param string $name
     * @param string|null $field
     * @param string|null $script
     * @return Bucketing
     */
    public function terms(string $name, ?string $field = null, ?string $script = null) : Bucketing
    {
        $this->addAggregation(new TermsAggregation($name, $field, $script));
        return $this;
    }
}
