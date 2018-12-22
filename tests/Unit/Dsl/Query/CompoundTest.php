<?php
namespace Tests\Unit\Dsl\Query;

use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Query\Compound;
use Triadev\Es\Dsl\Dsl\Search;

class CompoundTest extends TestCase
{
    /** @var Compound */
    private $compound;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->compound = new Compound();
    }
    
    /**
     * @test
     */
    public function it_builds_a_boosting_query()
    {
        $result = $this->compound->boosting(
            new TermQuery('FIELD1', 'VALUE1'),
            new TermQuery('FIELD2', 'VALUE2'),
            1.2
        )->toDsl();
        
        $this->assertEquals([
            'query' => [
                'boosting' => [
                    'positive' => [
                        'term' => [
                            'FIELD1' => 'VALUE1'
                        ]
                    ],
                    'negative' => [
                        'term' => [
                            'FIELD2' => 'VALUE2'
                        ]
                    ],
                    'negative_boost' => 1.2
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_constant_score_query()
    {
        $result = $this->compound->constantScore(function (Search $search) {
            $search->termLevel()->term('FIELD', 'VALUE');
        })->toDsl();
        
        $this->assertEquals([
            'query' => [
                'constant_score' => [
                    'filter' => [
                        'term' => [
                            'FIELD' => 'VALUE'
                        ]
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_dis_max_query()
    {
        $result = $this->compound->disMax([
            new TermQuery('FIELD', 'VALUE')
        ])->toDsl();
        
        $this->assertEquals([
            'query' => [
                'dis_max' => [
                    'queries' => [
                        [
                            'term' => [
                                'FIELD' => 'VALUE'
                            ]
                        ]
                    ]
                ]
            ]
        ], $result);
    }
}
