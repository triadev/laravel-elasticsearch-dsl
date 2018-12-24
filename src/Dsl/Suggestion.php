<?php
namespace Triadev\Es\Dsl\Dsl;

use ONGR\ElasticsearchDSL\Suggest\Suggest;
use Triadev\Es\Dsl\Facade\ElasticDsl;

class Suggestion extends AbstractSearch
{
    /**
     * Get suggestion result as array
     *
     * @return array
     */
    public function get() : array
    {
        return ElasticDsl::getEsClient()->suggest([
            'index' => $this->getEsIndex(),
            'body' => $this->toDsl()
        ]);
    }
    
    /**
     * Term
     *
     * @param string $name
     * @param string $text
     * @param string $field
     * @param array $params
     * @return Suggestion
     */
    public function term(string $name, string $text, string $field, array $params = []): Suggestion
    {
        $this->search->addSuggest(new Suggest(
            $name,
            'term',
            $text,
            $field,
            $params
        ));
        
        return $this;
    }
    
    /**
     * Phrase
     *
     * @param string $name
     * @param string $text
     * @param string $field
     * @param array $params
     * @return Suggestion
     */
    public function phrase(string $name, string $text, string $field, array $params = []): Suggestion
    {
        $this->search->addSuggest(new Suggest(
            $name,
            'phrase',
            $text,
            $field,
            $params
        ));
        
        return $this;
    }
    
    /**
     * Term
     *
     * @param string $name
     * @param string $text
     * @param string $field
     * @param array $params
     * @return Suggestion
     */
    public function completion(string $name, string $text, string $field, array $params = []): Suggestion
    {
        $this->search->addSuggest(new Suggest(
            $name,
            'completion',
            $text,
            $field,
            $params
        ));
        
        return $this;
    }
}
