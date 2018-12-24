<?php
namespace Triadev\Es\Dsl\Dsl\Query;

use ONGR\ElasticsearchDSL\Query\Specialized\MoreLikeThisQuery;
use Triadev\Es\Dsl\Dsl\AbstractDsl;

class Specialized extends AbstractDsl
{
    /**
     * More like this
     *
     * @param string $like
     * @param array $params
     * @return Specialized
     */
    public function moreLikeThis(string $like, array $params = []): Specialized
    {
        return $this->append(new MoreLikeThisQuery($like, $params));
    }
}
