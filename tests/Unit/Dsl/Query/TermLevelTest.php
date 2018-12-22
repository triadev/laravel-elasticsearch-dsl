<?php
namespace Tests\Unit\Dsl\Query;

use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Query\TermLevel;

class TermLevelTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_bool_terms_query()
    {
        $result = (new TermLevel())
            ->must()
                ->term('FIELD_MUST', 'VALUE_MUST')
            ->mustNot()
                ->term('FIELD_MUST_NOT', 'VALUE_MUST_NOT')
            ->should()
                ->term('FIELD_SHOULD', 'VALUE_SHOULD')
            ->filter()
                ->term('FIELD_FILTER', 'VALUE_FILTER')
                ->exists('FIELD')
                ->fuzzy('FIELD', 'VALUE')
                ->ids([1,2,3])
                ->prefix('FIELD', 'VALUE')
                ->range('FIELD', [
                    'gt' => 10,
                    'lt' => 20
                ])
                ->regexp('FIELD', 'VALUE')
                ->terms('FIELD', [
                    'VALUE1',
                    'VALUE2'
                ])
                ->type('TYPE')
                ->wildcard('FIELD', 'VALUE')
            ->toDsl();
        
        $this->assertEquals([
            [
                'term' => [
                    'FIELD_MUST' => 'VALUE_MUST'
                ]
            ]
        ], array_get($result, 'query.bool.must'));
        
        $this->assertEquals([
            [
                'term' => [
                    'FIELD_MUST_NOT' => 'VALUE_MUST_NOT'
                ]
            ]
        ], array_get($result, 'query.bool.must_not'));
        
        $this->assertEquals([
            [
                'term' => [
                    'FIELD_SHOULD' => 'VALUE_SHOULD'
                ]
            ]
        ], array_get($result, 'query.bool.should'));
        
        $this->assertEquals([
            [
                'term' => [
                    'FIELD_FILTER' => 'VALUE_FILTER'
                ]
            ],
            [
                'exists' => [
                    'field' => 'FIELD'
                ]
            ],
            [
                'fuzzy' => [
                    'FIELD' => [
                        'value' => 'VALUE'
                    ]
                ]
            ],
            [
                'ids' => [
                    'values' => [1,2,3]
                ]
            ],
            [
                'prefix' => [
                    'FIELD' => [
                        'value' => 'VALUE'
                    ]
                ]
            ],
            [
                'range' => [
                    'FIELD' => [
                        'gt' => 10,
                        'lt' => 20
                    ]
                ]
            ],
            [
                'regexp' => [
                    'FIELD' => [
                        'value' => 'VALUE'
                    ]
                ]
            ],
            [
                'terms' => [
                    'FIELD' => [
                        'VALUE1',
                        'VALUE2'
                    ]
                ]
            ],
            [
                'type' => [
                    'value' => 'TYPE'
                ]
            ],
            [
                'wildcard' => [
                    'FIELD' => [
                        'value' => 'VALUE'
                    ]
                ]
            ]
        ], array_get($result, 'query.bool.filter'));
    }
}
