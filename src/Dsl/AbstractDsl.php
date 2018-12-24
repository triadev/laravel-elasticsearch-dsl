<?php
namespace Triadev\Es\Dsl\Dsl;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use Triadev\Es\Dsl\Dsl\Query\Compound;
use Triadev\Es\Dsl\Dsl\Query\Fulltext;
use Triadev\Es\Dsl\Dsl\Query\Geo;
use Triadev\Es\Dsl\Dsl\Query\InnerHit;
use Triadev\Es\Dsl\Dsl\Query\Joining;
use Triadev\Es\Dsl\Dsl\Query\Specialized;
use Triadev\Es\Dsl\Dsl\Query\TermLevel;
use Triadev\Es\Dsl\Facade\ElasticDsl;

/**
 * Class AbstractDsl
 * @package Triadev\Es\Dsl\Dsl
 *
 * @method TermLevel termLevel()
 * @method Fulltext fulltext()
 * @method Geo geo()
 * @method Compound compound()
 * @method Joining joining()
 * @method Specialized specialized()
 * @method InnerHit innerHit()
 */
abstract class AbstractDsl extends AbstractSearch
{
    /** @var string */
    private $boolState = BoolQuery::MUST;
    
    /**
     * Append
     *
     * @param BuilderInterface $query
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function append(BuilderInterface $query) : AbstractDsl
    {
        $this->search->addQuery($query, $this->boolState);
        return $this;
    }
    
    /**
     * Bool state: must
     *
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function must(): AbstractDsl
    {
        $this->boolState = BoolQuery::MUST;
        return $this;
    }
    
    /**
     * Bool state: must not
     *
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function mustNot(): AbstractDsl
    {
        $this->boolState = BoolQuery::MUST_NOT;
        return $this;
    }
    
    /**
     * Bool state: should
     *
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function should(): AbstractDsl
    {
        $this->boolState = BoolQuery::SHOULD;
        return $this;
    }
    
    /**
     * Bool state: filter
     *
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function filter(): AbstractDsl
    {
        $this->boolState = BoolQuery::FILTER;
        return $this;
    }
    
    /**
     * Paginate
     *
     * @param int $page
     * @param int $limit
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function paginate(int $page, int $limit = 25) : AbstractDsl
    {
        $this->search->setFrom($limit * ($page - 1))->setSize($limit);
        return $this;
    }
    
    /**
     * Min score
     *
     * @param int $minScore
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function minScore(int $minScore) : AbstractDsl
    {
        $this->search->setMinScore($minScore);
        return $this;
    }
    
    /**
     * Sort
     *
     * @param string $field
     * @param string $order
     * @param array $params
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function sort(string $field, string $order = FieldSort::DESC, array $params = []) : AbstractDsl
    {
        $this->search->addSort(new FieldSort(
            $field,
            $order,
            $params
        ));
        
        return $this;
    }
    
    /**
     * Aggregation
     *
     * @param \Closure $aggregation
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function aggregation(\Closure $aggregation) : AbstractDsl
    {
        $aggregation(new Aggregation($this->search));
        return $this;
    }
    
    /**
     * Search
     *
     * @param \Closure $search
     * @return AbstractDsl|Search|TermLevel|Compound|Fulltext|Geo|InnerHit|Joining|Specialized
     */
    public function bool(\Closure $search) : AbstractDsl
    {
        $searchBuilder = ElasticDsl::search();
        $search($searchBuilder);
        
        $this->append($searchBuilder->getQuery());
        
        return $this;
    }
    
    /**
     * Call
     *
     * @param string $name
     * @param array $arguments
     *
     * @return AbstractDsl|null
     */
    public function __call(string $name, array $arguments) : ?AbstractDsl
    {
        $validFunctions = [
            'termLevel',
            'fulltext',
            'geo',
            'compound',
            'joining',
            'specialized',
            'innerHit'
        ];
        
        if (in_array($name, $validFunctions)) {
            return ElasticDsl::search($this->search)->$name();
        }
        
        return null;
    }
}
