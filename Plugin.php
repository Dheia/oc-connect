<?php namespace Codecycler\Connect;

use App;
use File;
use Config;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;
use Codecycler\Connect\Classes\SchemaManager;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    /**
     * Boots (configures and registers) any packages found within this plugin's packages.load configuration value
     *
     * @see https://luketowers.ca/blog/how-to-use-laravel-packages-in-october-plugins
     * @author Luke Towers <octobercms@luketowers.ca>
     */
    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));

        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();

        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                Config::set($options['config_namespace'], $options['config']);
            }

            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    App::register($provider);
                }
            }

            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }
        }
    }

    public function registerMiddlewares()
    {
        $this->app['router']->aliasMiddleware('api-checkauth', \Codecycler\Connect\Middleware\CheckAuth::class);
    }

    public function boot()
    {
        $this->bootPackages();
        $this->registerMiddlewares();
        $this->createDefaultSchema();

        App::singleton(\Nuwave\Lighthouse\Schema\Source\SchemaSourceProvider::class, \Codecycler\Connect\Classes\SchemaSourceProvider::class);
        App::make('October\Rain\Support\ClassLoader')->addDirectories(base_path('graphql'));
        SchemaManager::instance()->registerModelNamespaces();
    }

    public function createDefaultSchema()
    {
        if (!file_exists(base_path('graphql/schema.graphql'))) {
            if (!file_exists(base_path('graphql'))) {
                mkdir(base_path('graphql'), 0777, true);
            }

            copy(plugins_path('codecycler/connect/assets/schema.graphql'), base_path('graphql/schema.graphql'));
        }
    }
}
