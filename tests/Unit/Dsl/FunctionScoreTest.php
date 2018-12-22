<?php
namespace Tests\Unit\Dsl;

use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\FunctionScore;
use Triadev\Es\Dsl\Dsl\Search;
use Triadev\Es\Dsl\Facade\ElasticDsl;

class FunctionScoreTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_field_function_score_query()
    {
        $result = ElasticDsl::search()->compound()->functionScore(
            function (Search $search) {
                $search->termLevel()->term('FIELD1', 'VALUE1');
            },
            function (FunctionScore $functionScore) {
                $functionScore->field(
                    'FIELD2',
                    0.2,
                    'none',
                    function (Search $search) {
                        $search->termLevel()->term('FIELD3', 'VALUE3');
                    }
                );
            }
        )->toDsl();
        
        $this->assertEquals([
            'query' => [
                'function_score' => [
                    'query' => [
                        'term' => [
                            'FIELD1' => 'VALUE1'
                        ]
                    ],
                    'functions' => [
                        [
                            'field_value_factor' => [
                                'field' => 'FIELD2',
                                'factor' => 0.2,
                                'modifier' => 'none'
                            ],
                            'filter' => [
                                'term' => [
                                    'FIELD3' => 'VALUE3'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_decay_function_score_query()
    {
        $result = ElasticDsl::search()->compound()->functionScore(
            function (Search $search) {
                $search->termLevel()->term('FIELD1', 'VALUE1');
            },
            function (FunctionScore $functionScore) {
                $functionScore->decay(
                    'TYPE',
                    'FIELD',
                    [],
                    [],
                    function (Search $search) {
                        $search->termLevel()->term('FIELD3', 'VALUE3');
                    },
                    2
                );
            }
        )->toDsl();
        
        $this->assertEquals([
            'query' => [
                'function_score' => [
                    'query' => [
                        'term' => [
                            'FIELD1' => 'VALUE1'
                        ]
                    ],
                    'functions' => [
                        [
                            'TYPE' => [
                                'FIELD' => []
                            ],
                            'weight' => 2,
                            'filter' => [
                                'term' => [
                                    'FIELD3' => 'VALUE3'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_weight_function_score_query()
    {
        $result = ElasticDsl::search()->compound()->functionScore(
            function (Search $search) {
                $search->termLevel()->term('FIELD1', 'VALUE1');
            },
            function (FunctionScore $functionScore) {
                $functionScore->weight(
                    1.1,
                    function (Search $search) {
                        $search->termLevel()->term('FIELD2', 'VALUE2');
                    }
                );
            }
        )->toDsl();
        
        $this->assertEquals([
            'query' => [
                'function_score' => [
                    'query' => [
                        'term' => [
                            'FIELD1' => 'VALUE1'
                        ]
                    ],
                    'functions' => [
                        [
                            'weight' => 1.1,
                            'filter' => [
                                'term' => [
                                    'FIELD2' => 'VALUE2'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_random_function_score_query()
    {
        $result = ElasticDsl::search()->compound()->functionScore(
            function (Search $search) {
                $search->termLevel()->term('FIELD1', 'VALUE1');
            },
            function (FunctionScore $functionScore) {
                $functionScore->random();
            }
        )->toDsl();
    
        $this->assertEquals([
            'query' => [
                'function_score' => [
                    'query' => [
                        'term' => [
                            'FIELD1' => 'VALUE1'
                        ]
                    ],
                    'functions' => [
                        [
                            'random_score' => new \stdClass()
                        ]
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_script_function_score_query()
    {
        $result = ElasticDsl::search()->compound()->functionScore(
            function (Search $search) {
                $search->termLevel()->term('FIELD1', 'VALUE1');
            },
            function (FunctionScore $functionScore) {
                $functionScore->script(
                    'INLINE'
                );
            }
        )->toDsl();
        
        $this->assertEquals([
            'query' => [
                'function_score' => [
                    'query' => [
                        'term' => [
                            'FIELD1' => 'VALUE1'
                        ]
                    ],
                    'functions' => [
                        [
                            'script_score' => [
                                'script' => [
                                    'lang' => 'painless',
                                    'inline' => 'INLINE'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_simple_function_score_query()
    {
        $result = ElasticDsl::search()->compound()->functionScore(
            function (Search $search) {
                $search->termLevel()->term('FIELD1', 'VALUE1');
            },
            function (FunctionScore $functionScore) {
                $functionScore->simple([]);
            }
        )->toDsl();
        
        $this->assertEquals([
            'query' => [
                'function_score' => [
                    'query' => [
                        'term' => [
                            'FIELD1' => 'VALUE1'
                        ]
                    ],
                    'functions' => [
                        []
                    ]
                ]
            ]
        ], $result);
    }
}
