<?php
namespace Triadev\Es\Dsl\Dsl\Query;

use Triadev\Es\Dsl\Dsl\AbstractDsl;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\ExistsQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\FuzzyQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\IdsQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\PrefixQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\RangeQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\RegexpQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermsQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TypeQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\WildcardQuery;

class TermLevel extends AbstractDsl
{
    /**
     * Match all
     *
     * @return TermLevel
     */
    public function matchAll() : TermLevel
    {
        return $this->append(new MatchAllQuery());
    }
    
    /**
     * Exists
     *
     * @param string $field
     * @return TermLevel
     */
    public function exists(string $field): TermLevel
    {
        return $this->append(new ExistsQuery($field));
    }
    
    /**
     * Fuzzy
     *
     * @param string $field
     * @param string $value
     * @param array $params
     * @return TermLevel
     */
    public function fuzzy(string $field, string $value, array $params = []): TermLevel
    {
        return $this->append(new FuzzyQuery($field, $value, $params));
    }
    
    /**
     * Ids
     *
     * @param array $values
     * @param array $params
     * @return TermLevel
     */
    public function ids(array $values, array $params = []): TermLevel
    {
        return $this->append(new IdsQuery($values, $params));
    }
    
    /**
     * Prefix
     *
     * @param string $field
     * @param string $value
     * @param array $params
     * @return TermLevel
     */
    public function prefix(string $field, string $value, array $params = []): TermLevel
    {
        return $this->append(new PrefixQuery($field, $value, $params));
    }
    
    /**
     * Range
     *
     * @param string $field
     * @param array $params
     * @return TermLevel
     */
    public function range(string $field, array $params = []): TermLevel
    {
        return $this->append(new RangeQuery($field, $params));
    }
    
    /**
     * Regexp
     *
     * @param string $field
     * @param string $value
     * @param array $params
     * @return TermLevel
     */
    public function regexp(string $field, string $value, array $params = []): TermLevel
    {
        return $this->append(new RegexpQuery($field, $value, $params));
    }
    
    /**
     * Term
     *
     * @param string $field
     * @param string $value
     * @return TermLevel
     */
    public function term(string $field, string $value): TermLevel
    {
        return $this->append(new TermQuery($field, $value));
    }
    
    /**
     * Terms
     *
     * @param string $field
     * @param array $values
     * @param array $params
     * @return TermLevel
     */
    public function terms(string $field, array $values, array $params = []): TermLevel
    {
        return $this->append(new TermsQuery($field, $values, $params));
    }
    
    /**
     * Type
     *
     * @param string $type
     * @return TermLevel
     */
    public function type(string $type) : TermLevel
    {
        return $this->append(new TypeQuery($type));
    }
    
    /**
     * Wildcard
     *
     * @param string $field
     * @param string $value
     * @param array $params
     * @return TermLevel
     */
    public function wildcard(string $field, string $value, array $params = []): TermLevel
    {
        return $this->append(new WildcardQuery($field, $value, $params));
    }
}
