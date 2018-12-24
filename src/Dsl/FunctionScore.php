<?php
namespace Triadev\Es\Dsl\Dsl;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Query\Compound\FunctionScoreQuery;

class FunctionScore
{
    /** @var FunctionScoreQuery */
    private $query;
    
    /**
     * FunctionScore constructor.
     * @param BuilderInterface $query
     * @param array $params
     */
    public function __construct(BuilderInterface $query, array $params = [])
    {
        $this->query = new FunctionScoreQuery($query, $params);
    }
    
    /**
     * Get query
     *
     * @return BuilderInterface
     */
    public function getQuery() : BuilderInterface
    {
        return $this->query;
    }
    
    /**
     * Field
     *
     * @param string $field
     * @param float $factor
     * @param string $modifier
     * @param \Closure|null $search
     * @return FunctionScore
     */
    public function field(
        string $field,
        float $factor,
        string $modifier = 'none',
        ?\Closure $search = null
    ) : FunctionScore {
        $this->query->addFieldValueFactorFunction(
            $field,
            $factor,
            $modifier,
            $this->buildQueryFromSearchClosure($search)
        );
        
        return $this;
    }
    
    /**
     * Decay
     *
     * @param string $type
     * @param string $field
     * @param array $function
     * @param array $options
     * @param \Closure|null $search
     * @param int|null $weight
     * @return FunctionScore
     */
    public function decay(
        string $type,
        string $field,
        array $function,
        array $options = [],
        ?\Closure $search = null,
        int $weight = null
    ) : FunctionScore {
        $this->query->addDecayFunction(
            $type,
            $field,
            $function,
            $options,
            $this->buildQueryFromSearchClosure($search),
            $weight
        );
        
        return $this;
    }
    
    /**
     * Weight
     *
     * @param float $weight
     * @param \Closure|null $search
     * @return FunctionScore
     */
    public function weight(float $weight, ?\Closure $search = null) : FunctionScore
    {
        $this->query->addWeightFunction($weight, $this->buildQueryFromSearchClosure($search));
        return $this;
    }
    
    /**
     * Random
     *
     * @param int|null $seed
     * @param \Closure|null $search
     * @return FunctionScore
     */
    public function random(?int $seed = null, ?\Closure $search = null) : FunctionScore
    {
        $this->query->addRandomFunction($seed, $this->buildQueryFromSearchClosure($search));
        return $this;
    }
    
    /**
     * Script
     *
     * @param string $inline
     * @param array $params
     * @param array $options
     * @param \Closure|null $search
     * @return FunctionScore
     */
    public function script(
        string $inline,
        array $params = [],
        array $options = [],
        ?\Closure $search = null
    ) : FunctionScore {
        $this->query->addScriptScoreFunction(
            $inline,
            $params,
            $options,
            $this->buildQueryFromSearchClosure($search)
        );
        
        return $this;
    }
    
    /**
     * Simple
     *
     * @param array $functions
     * @return FunctionScore
     */
    public function simple(array $functions) : FunctionScore
    {
        $this->query->addSimpleFunction($functions);
        return $this;
    }
    
    /**
     * Build query from search closure
     *
     * @param \Closure|null $search
     * @return BuilderInterface|null
     */
    private function buildQueryFromSearchClosure(?\Closure $search) : ?BuilderInterface
    {
        $query = null;
    
        if ($search) {
            $searchBuilder = app()->make(Search::class);
            $search($searchBuilder);
        
            $query = $searchBuilder->getQuery();
        }
        
        return $query;
    }
}
