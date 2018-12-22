<?php
namespace Triadev\Es\Dsl\Dsl\Query;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Query\Compound\BoostingQuery;
use ONGR\ElasticsearchDSL\Query\Compound\ConstantScoreQuery;
use ONGR\ElasticsearchDSL\Query\Compound\DisMaxQuery;
use Triadev\Es\Dsl\Dsl\AbstractDsl;
use Triadev\Es\Dsl\Dsl\FunctionScore;
use Triadev\Es\Dsl\Facade\ElasticDsl;

class Compound extends AbstractDsl
{
    /**
     * Function score
     *
     * @param \Closure $search
     * @param \Closure $functionScore
     * @param array $params
     * @return Compound
     */
    public function functionScore(\Closure $search, \Closure $functionScore, array $params = []) : Compound
    {
        $searchBuilder = ElasticDsl::search();
        $search($searchBuilder);
        
        $functionScoreBuilder = new FunctionScore($searchBuilder->getQuery(), $params);
        $functionScore($functionScoreBuilder);
        
        $this->append($functionScoreBuilder->getQuery());
        return $this;
    }
    
    /**
     * Constant score
     *
     * @param \Closure $search
     * @param array $params
     * @return Compound
     */
    public function constantScore(\Closure $search, array $params = []) : Compound
    {
        $searchBuilder = ElasticDsl::search();
        $search($searchBuilder);
        
        $this->append(new ConstantScoreQuery($searchBuilder->getQuery(), $params));
        return $this;
    }
    
    /**
     * Boosting
     *
     * @param BuilderInterface $positive
     * @param BuilderInterface $negative
     * @param float $negativeBoost
     * @return Compound
     */
    public function boosting(
        BuilderInterface $positive,
        BuilderInterface $negative,
        float $negativeBoost
    ) : Compound {
        $this->append(new BoostingQuery($positive, $negative, $negativeBoost));
        return $this;
    }
    
    /**
     * Dis max
     *
     * @param BuilderInterface[] $queries
     * @param array $params
     * @return Compound
     */
    public function disMax(array $queries, array $params = []) : Compound
    {
        $disMaxQuery = new DisMaxQuery($params);
        
        foreach ($queries as $query) {
            if ($query instanceof BuilderInterface) {
                $disMaxQuery->addQuery($query);
            }
        }
        
        $this->append($disMaxQuery);
        return $this;
    }
}
