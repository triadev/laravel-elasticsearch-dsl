<?php
namespace Tests\Unit\Dsl;

use ONGR\ElasticsearchDSL\Aggregation\Bucketing\HistogramAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\SignificantTermsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\AvgAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\MinAggregation;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Aggregation;

class AggregationTest extends TestCase
{
    /** @var Search */
    private $search;
    
    /** @var Aggregation */
    private $aggregation;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->search = new Search();
        $this->aggregation = new Aggregation($this->search);
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_children_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->children('CHILDREN', 'TYPE', function (Aggregation\Bucketing $bucketing) {
                $bucketing->terms('NAME', 'FIELD');
            });
        });
        
        $this->assertEquals([
            'aggregations' => [
                'CHILDREN' => [
                    'children' => [
                        'type' => 'TYPE'
                    ],
                    'aggregations' => [
                        'NAME' => [
                            'terms' => [
                                'field' => 'FIELD'
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_date_histogram_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->dateHistogram(
                'NAME',
                'FIELD',
                'month'
            );
        });
    
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'date_histogram' => [
                        'field' => 'FIELD',
                        'interval' => 'month'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_date_range_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->dateRange('NAME', 'FIELD', 'MM-yyyy', [
                [
                    'from' => 'now-10M/M',
                    'to' => 'now-10M/M'
                ]
            ]);
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'date_range' => [
                        'format' => 'MM-yyyy',
                        'field' => 'FIELD',
                        'ranges' => [
                            [
                                'from' => 'now-10M/M',
                                'to' => 'now-10M/M'
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_diversified_sampler_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->diversifiedSampler(
                'NAME',
                'FIELD'
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'diversified_sampler' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_filter_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $termFilter = new TermQuery('FIELD', 'VALUE');

            $bucketing->filter('NAME', $termFilter, [
                new AvgAggregation('AVG', 'FIELD')
            ]);
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'filter' => [
                        'term' => [
                            'FIELD' => 'VALUE'
                        ]
                    ],
                    'aggregations' => [
                        'AVG' => [
                            'avg' => [
                                'field' => 'FIELD'
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_filters_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $errorTermFilter = new TermQuery('body', 'error');
    
            $histogramAggregation = new HistogramAggregation('monthly', 'timestamp');
            $histogramAggregation->setInterval('1M');
            
            $bucketing->filters('NAME', [
                'error' => $errorTermFilter
            ], [
                $histogramAggregation
            ]);
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'filters' => [
                        'filters' => [
                            'error' => [
                                'term' => [
                                    'body' => 'error'
                                ]
                            ]
                        ]
                    ],
                    'aggregations' => [
                        'monthly' => [
                            'histogram' => [
                                'field' => 'timestamp',
                                'interval' => '1M'
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_geo_distance_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->geoDistance(
                'NAME',
                'location',
                '52.3760, 4.894',
                [
                    ['to' => 100]
                ]
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'geo_distance' => [
                        'field' => 'location',
                        'origin' => '52.3760, 4.894',
                        'ranges' => [
                            [
                                'to' => 100
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_geo_hash_grid_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->geoHashGrid(
                'NAME',
                'location',
                3
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'geohash_grid' => [
                        'field' => 'location',
                        'precision' => 3
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_histogram_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->histogram(
                'NAME',
                'FIELD',
                50
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'histogram' => [
                        'field' => 'FIELD',
                        'interval' => 50
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_ipv4_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->ipv4Range(
                'NAME',
                'FIELD',
                [
                    ['to' => '10.0.0.6'],
                    ['from' => '10.0.0.5'],
                ]
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'ip_range' => [
                        'field' => 'FIELD',
                        'ranges' => [
                            [
                                'to' => '10.0.0.6'
                            ],
                            [
                                'from' => '10.0.0.5'
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_missing_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->missing(
                'NAME',
                'FIELD'
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'missing' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_nested_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $minAggregation = new MinAggregation('NAME', 'FIELD');
            
            $bucketing->nested(
                'NAME',
                'PATH',
                [
                    $minAggregation
                ]
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'nested' => [
                        'path' => 'PATH'
                    ],
                    'aggregations' => [
                        'NAME' => [
                            'min' => [
                                'field' => 'FIELD'
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_range_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->range(
                'NAME',
                'FIELD',
                [
                    ['from' => 50, 'to' => 100]
                ]
            );
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'range' => [
                        'field' => 'FIELD',
                        'keyed' => false,
                        'ranges' => [
                            [
                                'from' => 50,
                                'to' => 100
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_sampler_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->sampler('NAME', 'FIELD', 100, [
                new SignificantTermsAggregation('keywords', 'text')
            ]);
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'sampler' => [
                        'field' => 'FIELD',
                        'shard_size' => 100
                    ],
                    'aggregations' => [
                        'keywords' => [
                            'significant_terms' => [
                                'field' => 'text'
                            ]
                        ]
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_significant_terms_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->significantTerms('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'significant_terms' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_bucketing_terms_aggregation()
    {
        $this->aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
            $bucketing->terms('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'terms' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_avg_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->avg('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'avg' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_extended_stats_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->extendedStats('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'extended_stats' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_cardinality_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->cardinality('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'cardinality' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_geo_bounds_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->geoBounds('NAME', 'FIELD', true);
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'geo_bounds' => [
                        'field' => 'FIELD',
                        'wrap_longitude' => true
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_geo_centroid_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->geoCentroid('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'geo_centroid' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_max_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->max('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'max' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_min_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->min('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'min' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_stats_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->stats('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'stats' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_sum_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->sum('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'sum' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_top_hits_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->topHits('NAME');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'top_hits' => new \stdClass()
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_metric_value_count_aggregation()
    {
        $this->aggregation->metric(function (Aggregation\Metric $bucketing) {
            $bucketing->valueCount('NAME', 'FIELD');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'value_count' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_avg_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->avg('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'avg_bucket' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_max_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->max('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'max_bucket' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_min_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->min('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'min_bucket' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_percentiles_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->percentiles('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'percentiles_bucket' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_stats_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->stats('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'stats_bucket' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_sum_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->sum('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'sum_bucket' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_serial_differencing_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->serialDifferencing('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'serial_diff' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_extended_stats_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->extendedStats('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'extended_stats_bucket' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_derivative_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->derivative('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'derivative' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_cumulative_sum_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->cumulativeSum('NAME', 'BUCKETS_PATH');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'cumulative_sum' => [
                        'buckets_path' => 'BUCKETS_PATH'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_bucket_selector_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->bucketSelector('NAME', 'BUCKETS_PATH', 'SCRIPT');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'bucket_selector' => [
                        'buckets_path' => 'BUCKETS_PATH',
                        'script' => 'SCRIPT'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
    
    /**
     * @test
     */
    public function it_builds_a_pipeline_bucket_script_aggregation()
    {
        $this->aggregation->pipeline(function (Aggregation\Pipeline $pipeline) {
            $pipeline->bucketScript('NAME', 'BUCKETS_PATH', 'SCRIPT');
        });
        
        $this->assertEquals([
            'aggregations' => [
                'NAME' => [
                    'bucket_script' => [
                        'buckets_path' => 'BUCKETS_PATH',
                        'script' => 'SCRIPT'
                    ]
                ]
            ]
        ], $this->search->toArray());
    }
}
