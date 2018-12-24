<?php
namespace Triadev\Es\Dsl\Dsl\Query;

use ONGR\ElasticsearchDSL\Query\FullText\CommonTermsQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchPhrasePrefixQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchPhraseQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MultiMatchQuery;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;
use ONGR\ElasticsearchDSL\Query\FullText\SimpleQueryStringQuery;
use Triadev\Es\Dsl\Dsl\AbstractDsl;

class Fulltext extends AbstractDsl
{
    /**
     * Match
     *
     * @param string $field
     * @param string $query
     * @param array $params
     * @return Fulltext
     */
    public function match(string $field, string $query, array $params = []) : Fulltext
    {
        return $this->append(new MatchQuery($field, $query, $params));
    }
    
    /**
     * Match phrase
     *
     * @param string $field
     * @param string $query
     * @param array $params
     * @return Fulltext
     */
    public function matchPhrase(string $field, string $query, array $params = []): Fulltext
    {
        return $this->append(new MatchPhraseQuery($field, $query, $params));
    }
    
    /**
     * Match phrase prefix
     *
     * @param string $field
     * @param string $query
     * @param array $params
     * @return Fulltext
     */
    public function matchPhrasePrefix(string $field, string $query, array $params = []): Fulltext
    {
        return $this->append(new MatchPhrasePrefixQuery($field, $query, $params));
    }
    
    /**
     * Multi match
     *
     * @param array $fields
     * @param string $query
     * @param array $params
     * @return Fulltext
     */
    public function multiMatch(array $fields, string $query, array $params = []): Fulltext
    {
        return $this->append(new MultiMatchQuery($fields, $query, $params));
    }
    
    /**
     * Query string
     *
     * @param string $query
     * @param array $params
     * @return Fulltext
     */
    public function queryString(string $query, array $params = []): Fulltext
    {
        return $this->append(new QueryStringQuery($query, $params));
    }
    
    /**
     * Simple query string
     *
     * @param string $query
     * @param array $params
     * @return Fulltext
     */
    public function simpleQueryString(string $query, array $params = []): Fulltext
    {
        return $this->append(new SimpleQueryStringQuery($query, $params));
    }
    
    /**
     * Common terms
     *
     * @param string $field
     * @param string $query
     * @param array $params
     * @return Fulltext
     */
    public function commonTerms(string $field, string $query, array $params = []): Fulltext
    {
        return $this->append(new CommonTermsQuery($field, $query, $params));
    }
}
