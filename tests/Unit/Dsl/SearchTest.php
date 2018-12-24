<?php
namespace Tests\Unit\Dsl;

use ONGR\ElasticsearchDSL\Sort\FieldSort;
use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Aggregation;
use Triadev\Es\Dsl\Dsl\Search;
use Triadev\Es\Dsl\Facade\ElasticDsl;

/**
 * Class SearchTest
 * @package Tests\Unit\Dsl
 */
class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_query_with_aggregation_sort_and_pagination()
    {
        $result = ElasticDsl::search()
            ->termLevel()
                ->filter()
                    ->term('FIELD', 'VALUE')
                    ->bool(function (Search $search) {
                        $search
                            ->termLevel()
                                ->term('FIELD1', 'VALUE1')
                                ->term('FIELD2', 'VALUE2');
                    })
            ->fulltext()
                ->must()
                    ->match('FIELD_MUST', 'QUERY_MUST')
                ->filter()
                    ->match('FIELD_FILTER', 'QUERY_FILTER')
            ->aggregation(function (Aggregation $aggregation) {
                $aggregation->bucketing(function (Aggregation\Bucketing $bucketing) {
                    $bucketing->terms('NAME', 'FIELD');
                });
            })
            ->sort('FIELD', FieldSort::ASC)
            ->paginate(10, 50)
            ->toDsl();
        
        $this->assertEquals([
            'bool' => [
                'filter' => [
                    [
                        'term' => [
                            'FIELD' => 'VALUE'
                        ]
                    ],
                    [
                        'bool' => [
                            'must' => [
                                [
                                    'term' => [
                                        'FIELD1' => 'VALUE1'
                                    ]
                                ],
                                [
                                    'term' => [
                                        'FIELD2' => 'VALUE2'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'match' => [
                            'FIELD_FILTER' => [
                                'query' => 'QUERY_FILTER'
                            ]
                        ]
                    ]
                ],
                'must' => [
                    [
                        'match' => [
                            'FIELD_MUST' => [
                                'query' => 'QUERY_MUST'
                            ]
                        ]
                    ]
                ]
            ]
        ], array_get($result, 'query'));
        
        $this->assertEquals([
            'NAME' => [
                'terms' => [
                    'field' => 'FIELD'
                ]
            ]
        ], array_get($result, 'aggregations'));
        
        $this->assertEquals(450, array_get($result, 'from'));
        $this->assertEquals(50, array_get($result, 'size'));
        
        $this->assertEquals([
            [
                'FIELD' => [
                    'order' => 'asc'
                ]
            ]
        ], array_get($result, 'sort'));
    }
}
