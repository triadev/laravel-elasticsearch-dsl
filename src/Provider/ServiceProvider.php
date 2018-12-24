<?php
namespace Triadev\Es\Dsl\Provider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Triadev\Es\Dsl\Contract\ElasticsearchDslContract;
use Triadev\Es\Dsl\ElasticsearchDsl;
use Triadev\Es\Dsl\Facade\ElasticDsl;
use Triadev\Es\Provider\ElasticsearchServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        ElasticsearchDslContract::class => ElasticsearchDsl::class
    ];
    
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../Config/config.php');
    
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('laravel-elasticsearch-dsl.php'),
        ], 'config');
    
        $this->mergeConfigFrom($source, 'laravel-elasticsearch-dsl');
    }
    
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(ElasticsearchServiceProvider::class);
    
        AliasLoader::getInstance()->alias('ElasticDsl', ElasticDsl::class);
    }
}
