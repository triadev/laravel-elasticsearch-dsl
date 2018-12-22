<?php
namespace Tests\Unit\Dsl;

use Tests\TestCase;
use Triadev\Es\Dsl\Facade\ElasticDsl;

class SuggestionTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_suggestion_term_query()
    {
        $this->assertEquals([
            'suggest' => [
                'NAME' => [
                    'text' => 'TEXT',
                    'term' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], ElasticDsl::suggest()->term('NAME', 'TEXT', 'FIELD')->toDsl());
    }
    
    /**
     * @test
     */
    public function it_builds_a_suggestion_phrase_query()
    {
        $this->assertEquals([
            'suggest' => [
                'NAME' => [
                    'text' => 'TEXT',
                    'phrase' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], ElasticDsl::suggest()->phrase('NAME', 'TEXT', 'FIELD')->toDsl());
    }
    
    /**
     * @test
     */
    public function it_builds_a_suggestion_completion_query()
    {
        $this->assertEquals([
            'suggest' => [
                'NAME' => [
                    'text' => 'TEXT',
                    'completion' => [
                        'field' => 'FIELD'
                    ]
                ]
            ]
        ], ElasticDsl::suggest()->completion('NAME', 'TEXT', 'FIELD')->toDsl());
    }
}