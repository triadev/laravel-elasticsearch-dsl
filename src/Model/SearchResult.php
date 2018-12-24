<?php
namespace Triadev\Es\Dsl\Model;

use Illuminate\Support\Collection;

class SearchResult
{
    /** @var int */
    private $took;
    
    /** @var bool */
    private $timedOut;
    
    /** @var array */
    private $shards;
    
    /** @var Collection */
    private $hits;
    
    /** @var int */
    private $totalHits;
    
    /** @var float */
    private $maxScore;
    
    /** @var array|null */
    private $aggregation;
    
    /**
     * SearchResult constructor.
     * @param array $result
     */
    public function __construct(array $result)
    {
        $this->took = (int)array_get($result, 'took');
        $this->timedOut = (bool)array_get($result, 'timed_out');
        $this->shards = (array)array_get($result, '_shards');
        $this->hits = new Collection(array_get($result, 'hits.hits'));
        $this->totalHits = (int)array_get($result, 'hits.total');
        $this->maxScore = (float)array_get($result, 'hits.max_score');
        $this->aggregation = array_get($result, 'aggregations', null);
    }
    
    /**
     * @return int
     */
    public function getTook(): int
    {
        return $this->took;
    }
    
    /**
     * @return bool
     */
    public function isTimedOut(): bool
    {
        return $this->timedOut;
    }
    
    /**
     * @return array
     */
    public function getShards(): array
    {
        return $this->shards;
    }
    
    /**
     * @return Collection
     */
    public function getHits(): Collection
    {
        return $this->hits;
    }
    
    /**
     * @param Collection $hits
     */
    public function setHits(Collection $hits)
    {
        $this->hits = $hits;
    }
    
    /**
     * @return int
     */
    public function getTotalHits(): int
    {
        return $this->totalHits;
    }
    
    /**
     * @return float
     */
    public function getMaxScore(): float
    {
        return $this->maxScore;
    }
    
    /**
     * @return array|null
     */
    public function getAggregation(): ?array
    {
        return $this->aggregation;
    }
}
