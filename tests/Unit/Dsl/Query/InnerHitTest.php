<?php
namespace Tests\Unit\Dsl\Query;

use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Query\InnerHit;
use Triadev\Es\Dsl\Dsl\Search;

class InnerHitTest extends TestCase
{
    /** @var InnerHit */
    private $innerHit;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->innerHit = new InnerHit();
    }
    
    /**
     * @test
     */
    public function it_builds_a_nested_inner_hit_query()
    {
        $result = $this->innerHit->nestedInnerHit('NAME', 'PATH', function (Search $search) {
            $search->termLevel()->term('FIELD', 'VALUE');
        })->toDsl();
        
        $this->assertEquals([
            'inner_hits' => [
                'NAME' => [
                    'path' => [
                        'PATH' => [
                            'query' => [
                                'term' => [
                                    'FIELD' => 'VALUE'
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
    public function it_builds_a_parent_inner_hit_query()
    {
        $result = $this->innerHit->parentInnerHit('NAME', 'PATH', function (Search $search) {
            $search->termLevel()->term('FIELD', 'VALUE');
        })->toDsl();
        
        $this->assertEquals([
            'inner_hits' => [
                'NAME' => [
                    'type' => [
                        'PATH' => [
                            'query' => [
                                'term' => [
                                    'FIELD' => 'VALUE'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ], $result);
    }
}
