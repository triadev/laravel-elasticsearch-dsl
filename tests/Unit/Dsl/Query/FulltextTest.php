<?php
namespace Tests\Unit\Dsl\Query;

use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Query\Fulltext;

class FulltextTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_fulltext_query()
    {
        $result = (new Fulltext())
            ->must()
                ->match('FIELD', 'QUERY')
                ->matchPhrase('FIELD', 'QUERY')
                ->matchPhrasePrefix('FIELD', 'QUERY')
                ->multiMatch([
                    'FIELD1',
                    'FIELD2'
                ], 'QUERY')
                ->queryString('QUERY')
                ->simpleQueryString('QUERY')
                ->commonTerms('FIELD', 'QUERY')
                ->toDsl();
        
        $this->assertEquals([
            'match' => [
                'FIELD' => [
                    'query' => 'QUERY'
                ]
            ]
        ], array_get($result, 'query.bool.must.0'));
        
        $this->assertEquals([
            'match_phrase' => [
                'FIELD' => [
                    'query' => 'QUERY'
                ]
            ]
        ], array_get($result, 'query.bool.must.1'));
        
        $this->assertEquals([
            'match_phrase_prefix' => [
                'FIELD' => [
                    'query' => 'QUERY'
                ]
            ]
        ], array_get($result, 'query.bool.must.2'));
        
        $this->assertEquals([
            'multi_match' => [
                'fields' => [
                    'FIELD1',
                    'FIELD2'
                ],
                'query' => 'QUERY'
            ]
        ], array_get($result, 'query.bool.must.3'));
        
        
        $this->assertEquals([
            'query_string' => [
                'query' => 'QUERY'
            ]
        ], array_get($result, 'query.bool.must.4'));
        
        $this->assertEquals([
            'simple_query_string' => [
                'query' => 'QUERY'
            ]
        ], array_get($result, 'query.bool.must.5'));
        
        $this->assertEquals([
            'common' => [
                'FIELD' => [
                    'query' => 'QUERY'
                ]
            ]
        ], array_get($result, 'query.bool.must.6'));
    }
}
