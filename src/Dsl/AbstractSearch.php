<?php
namespace Triadev\Es\Dsl\Dsl;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Search as OngrSearch;

abstract class AbstractSearch
{
    /** @var OngrSearch */
    protected $search;
    
    /** @var string|null */
    private $esIndex;
    
    /** @var string|null */
    private $esType;
    
    /**
     * AbstractDsl constructor.
     * @param OngrSearch|null $search
     */
    public function __construct(?OngrSearch $search = null)
    {
        $this->search = $search ?: new OngrSearch();
    }
    
    /**
     * Overwrite the default elasticsearch index
     *
     * @param string $index
     * @return AbstractSearch
     */
    public function esIndex(string $index) : AbstractSearch
    {
        $this->esIndex = $index;
        return $this;
    }
    
    /**
     * Get elasticsearch index
     *
     * @return string
     */
    public function getEsIndex() : string
    {
        return $this->esIndex ?: config('laravel-elasticsearch-dsl.index');
    }
    
    /**
     * Overwrite the default elasticsearch type
     *
     * @param string $type
     * @return AbstractSearch
     */
    public function esType(string $type) : AbstractSearch
    {
        $this->esType = $type;
        return $this;
    }
    
    /**
     * Get elasticsearch type
     *
     * @return string|null
     */
    public function getEsType() : ?string
    {
        return $this->esType;
    }
    
    /**
     * Get current search
     *
     * @return OngrSearch
     */
    protected function getCurrentSearch() : OngrSearch
    {
        return $this->search;
    }
    
    /**
     * To dsl
     *
     * @return array
     */
    public function toDsl() : array
    {
        return $this->search->toArray();
    }
    
    /**
     * Get search
     *
     * @return OngrSearch
     */
    public function getSearch() : OngrSearch
    {
        return $this->search;
    }
    
    /**
     * Get query
     *
     * @return BuilderInterface
     */
    public function getQuery() : BuilderInterface
    {
        return $this->search->getQueries();
    }
}
