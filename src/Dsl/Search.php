<?php
namespace Triadev\Es\Dsl\Dsl;

use Triadev\Es\Dsl\Dsl\Query\Compound;
use Triadev\Es\Dsl\Dsl\Query\Fulltext;
use Triadev\Es\Dsl\Dsl\Query\Geo;
use Triadev\Es\Dsl\Dsl\Query\InnerHit;
use Triadev\Es\Dsl\Dsl\Query\Joining;
use Triadev\Es\Dsl\Dsl\Query\Specialized;
use Triadev\Es\Dsl\Dsl\Query\TermLevel;

class Search extends AbstractDsl
{
    /**
     * Term level
     *
     * @return TermLevel
     */
    public function termLevel() : TermLevel
    {
        return new TermLevel($this->getCurrentSearch());
    }
    
    /**
     * Fulltext
     *
     * @return Fulltext
     */
    public function fulltext() : Fulltext
    {
        return new Fulltext($this->search);
    }
    
    /**
     * Geo
     *
     * @return Geo
     */
    public function geo() : Geo
    {
        return new Geo($this->search);
    }
    
    /**
     * Compound
     *
     * @return Compound
     */
    public function compound() : Compound
    {
        return new Compound($this->search);
    }
    
    /**
     * Joining
     *
     * @return Joining
     */
    public function joining() : Joining
    {
        return new Joining($this->search);
    }
    
    /**
     * Specialized
     *
     * @return Specialized
     */
    public function specialized() : Specialized
    {
        return new Specialized($this->search);
    }
    
    /**
     * Inner hit
     *
     * @return InnerHit
     */
    public function innerHit() : InnerHit
    {
        return new InnerHit($this->search);
    }
}
