<?php
namespace Tests\Unit\Dsl\Query;

use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Query\Joining;
use Triadev\Es\Dsl\Dsl\Search;

class JoiningTest extends TestCase
{
    /** @var Joining */
    private $joining;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    
        $this->joining = new Joining();
    }
    
    /**
     * @test
     */
    public function it_builds_a_nested_query()
    {
        $result = $this->joining->nested('PATH', function (Search $search) {
            $search->termLevel()->filter()
                ->term('FIELD1', 'VALUE1')
                ->term('FIELD2', 'VALUE2');
        })->toDsl();
        
        $this->assertEquals([
            'query' => [
                'nested' => [
                    'path' => 'PATH',
                    'query' => [
                        'bool' => [
                            'filter' => [
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
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_has_child_query()
    {
        $result = $this->joining->hasChild('TYPE', function (Search $search) {
            $search->termLevel()->filter()
                ->term('FIELD1', 'VALUE1')
                ->term('FIELD2', 'VALUE2');
        })->toDsl();
        
        $this->assertEquals([
            'query' => [
                'has_child' => [
                    'type' => 'TYPE',
                    'query' => [
                        'bool' => [
                            'filter' => [
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
                    ]
                ]
            ]
        ], $result);
    }
    
    /**
     * @test
     */
    public function it_builds_a_has_parent_query()
    {
        $result = $this->joining->hasParent('TYPE', function (Search $search) {
            $search->termLevel()->filter()
                ->term('FIELD1', 'VALUE1')
                ->term('FIELD2', 'VALUE2');
        })->toDsl();
        
        $this->assertEquals([
            'query' => [
                'has_parent' => [
                    'parent_type' => 'TYPE',
                    'query' => [
                        'bool' => [
                            'filter' => [
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
                    ]
                ]
            ]
        ], $result);
    }
}
