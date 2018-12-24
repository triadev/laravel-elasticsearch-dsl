<?php
namespace Triadev\Es\Dsl\Dsl\Query;

use Triadev\Es\Dsl\Dsl\AbstractDsl;
use ONGR\ElasticsearchDSL\Query\Joining\NestedQuery;
use ONGR\ElasticsearchDSL\Query\Joining\HasChildQuery;
use ONGR\ElasticsearchDSL\Query\Joining\HasParentQuery;
use Triadev\Es\Dsl\Facade\ElasticDsl;

class Joining extends AbstractDsl
{
    /**
     * Nested
     *
     * @param string $path
     * @param \Closure $search
     * @param array $params
     * @return Joining
     */
    public function nested(string $path, \Closure $search, array $params = []) : Joining
    {
        $searchBuilder = ElasticDsl::search();
        $search($searchBuilder);
        
        return $this->append(
            new NestedQuery(
                $path,
                $searchBuilder->getQuery(),
                $params
            )
        );
    }
    
    /**
     * Has child
     *
     * @param string $type
     * @param \Closure $search
     * @param array $params
     * @return Joining
     */
    public function hasChild(string $type, \Closure $search, array $params = []): Joining
    {
        $searchBuilder = ElasticDsl::search();
        $search($searchBuilder);
        
        return $this->append(new HasChildQuery($type, $searchBuilder->getQuery(), $params));
    }
    
    /**
     * Has parent
     *
     * @param string $type
     * @param \Closure $search
     * @param array $params
     * @return Joining
     */
    public function hasParent(string $type, \Closure $search, array $params = []): Joining
    {
        $searchBuilder = ElasticDsl::search();
        $search($searchBuilder);
        
        return $this->append(new HasParentQuery($type, $searchBuilder->getQuery(), $params));
    }
}
