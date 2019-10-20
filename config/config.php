<?php return [
    // This contains the Laravel Packages that you want this plugin to utilize listed under their package identifiers
    'packages' => [
        'nuwave/lighthouse' => [
            // Service providers to be registered by your plugin
            'providers' => [
                'Nuwave\Lighthouse\LighthouseServiceProvider',
            ],

            // Aliases to be registered by your plugin in the form of $alias => $pathToFacade
            'aliases' => [],

            // The namespace to set the configuration under. For this example, this package accesses it's config via config('purifier.' . $key), so the namespace 'purifier' is what we put here
            'config_namespace' => 'lighthouse',

            // The configuration file for the package itself. Start this out by copying the default one that comes with the package and then modifying what you need.
            'config' => [
                /*
                |--------------------------------------------------------------------------
                | Route Configuration
                |--------------------------------------------------------------------------
                |
                | Controls the HTTP route that your GraphQL server responds to.
                | You may set `route` => false, to disable the default route
                | registration and take full control.
                |
                */
                'route' => [
                    /*
                     * The URI the endpoint responds to, e.g. mydomain.com/graphql.
                     */
                    'uri' => 'graphql',
                    /*
                     * Lighthouse creates a named route for convenient URL generation and redirects.
                     */
                    'name' => 'graphql',
                    /*
                     *
                     * Beware that middleware defined here runs before the GraphQL execution phase,
                     * so you have to take extra care to return spec-compliant error responses.
                     * To apply middleware on a field level, use the @middleware directive.
                     */
                    'middleware' => [
                        \Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson::class,
                    ],
                    /*
                     * The `prefix` and `domain` configuration options are optional.
                     */
                    //'prefix' => '',
                    //'domain' => '',
                ],
                /*
                |--------------------------------------------------------------------------
                | Schema Location
                |--------------------------------------------------------------------------
                |
                | This is a path that points to where your GraphQL schema is located
                | relative to the app path. You should define your entire GraphQL
                | schema in this file (additional files may be imported).
                |
                */
                'schema' => [
                    'register' => base_path('graphql/schema.graphql'),
                ],
                /*
                |--------------------------------------------------------------------------
                | Schema Cache
                |--------------------------------------------------------------------------
                |
                | A large part of schema generation consists of parsing and AST manipulation.
                | This operation is very expensive, so it is highly recommended to enable
                | caching of the final schema to optimize performance of large schemas.
                |
                */
                'cache' => [
                    'enable' => env('LIGHTHOUSE_CACHE_ENABLE', env('APP_ENV') !== 'local'),
                    'key' => env('LIGHTHOUSE_CACHE_KEY', 'lighthouse-schema'),
                    'ttl' => env('LIGHTHOUSE_CACHE_TTL', null),
                ],
                /*
                |--------------------------------------------------------------------------
                | Namespaces
                |--------------------------------------------------------------------------
                |
                | These are the default namespaces where Lighthouse looks for classes to
                | extend functionality of the schema. You may pass in either a string
                | or an array, they are tried in order and the first match is used.
                |
                */
                'namespaces' => [
                    'models' => ['App', 'App\\Models'],
                    'queries' => 'App\\GraphQL\\Queries',
                    'mutations' => 'App\\GraphQL\\Mutations',
                    'subscriptions' => 'App\\GraphQL\\Subscriptions',
                    'interfaces' => 'App\\GraphQL\\Interfaces',
                    'unions' => 'App\\GraphQL\\Unions',
                    'scalars' => 'App\\GraphQL\\Scalars',
                    'directives' => [
                        'App\\GraphQL\\Directives',
                        'Api\\Schema\\Directives',
                    ],
                ],
                /*
                |--------------------------------------------------------------------------
                | Security
                |--------------------------------------------------------------------------
                |
                | Control how Lighthouse handles security related query validation.
                | Read more at http://webonyx.github.io/graphql-php/security/
                |
                */
                'security' => [
                    'max_query_complexity' => \GraphQL\Validator\Rules\QueryComplexity::DISABLED,
                    'max_query_depth' => \GraphQL\Validator\Rules\QueryDepth::DISABLED,
                    'disable_introspection' => \GraphQL\Validator\Rules\DisableIntrospection::DISABLED,
                ],
                /*
                |--------------------------------------------------------------------------
                | Pagination
                |--------------------------------------------------------------------------
                |
                | Limits the maximum "count" that users may pass as an argument
                | to fields that are paginated with the @paginate directive.
                | A setting of "null" means the count is unrestricted.
                |
                */
                'paginate_max_count' => null,
                /*
                |--------------------------------------------------------------------------
                | Pagination Amount Argument
                |--------------------------------------------------------------------------
                |
                | Set the name to use for the generated argument on paginated fields
                | that controls how many results are returned.
                |
                | DEPRECATED This setting will be removed in v5.
                |
                */
                'pagination_amount_argument' => 'first',
                /*
                |--------------------------------------------------------------------------
                | Debug
                |--------------------------------------------------------------------------
                |
                | Control the debug level as described in http://webonyx.github.io/graphql-php/error-handling/
                | Debugging is only applied if the global Laravel debug config is set to true.
                |
                */
                'debug' => \GraphQL\Error\Debug::INCLUDE_DEBUG_MESSAGE | \GraphQL\Error\Debug::INCLUDE_TRACE,
                /*
                |--------------------------------------------------------------------------
                | Error Handlers
                |--------------------------------------------------------------------------
                |
                | Register error handlers that receive the Errors that occur during execution
                | and handle them. You may use this to log, filter or format the errors.
                | The classes must implement \Nuwave\Lighthouse\Execution\ErrorHandler
                |
                */
                'error_handlers' => [
                    \Nuwave\Lighthouse\Execution\ExtensionErrorHandler::class,
                ],
                /*
                |--------------------------------------------------------------------------
                | Global ID
                |--------------------------------------------------------------------------
                |
                | The name that is used for the global id field on the Node interface.
                | When creating a Relay compliant server, this must be named "id".
                |
                */
                'global_id_field' => 'id',
                /*
                |--------------------------------------------------------------------------
                | Batched Queries
                |--------------------------------------------------------------------------
                |
                | GraphQL query batching means sending multiple queries to the server in one request,
                | You may set this flag to either process or deny batched queries.
                |
                */
                'batched_queries' => true,
                /*
                |--------------------------------------------------------------------------
                | Transactional Mutations
                |--------------------------------------------------------------------------
                |
                | If set to true, mutations such as @create or @update will be
                | wrapped in a transaction to ensure atomicity.
                |
                */
                'transactional_mutations' => true,
                /*
                |--------------------------------------------------------------------------
                | GraphQL Subscriptions
                |--------------------------------------------------------------------------
                |
                | Here you can define GraphQL subscription "broadcasters" and "storage" drivers
                | as well their required configuration options.
                |
                */
                'subscriptions' => [
                    /*
                     * Determines if broadcasts should be queued by default.
                     */
                    'queue_broadcasts' => env('LIGHTHOUSE_QUEUE_BROADCASTS', true),
                    /*
                     * Default subscription storage.
                     *
                     * Any Laravel supported cache driver options are available here.
                     */
                    'storage' => env('LIGHTHOUSE_SUBSCRIPTION_STORAGE', 'redis'),
                    /*
                     * Default subscription broadcaster.
                     */
                    'broadcaster' => env('LIGHTHOUSE_BROADCASTER', 'pusher'),
                    /*
                     * Subscription broadcasting drivers with config options.
                     */
                    'broadcasters' => [
                        'log' => [
                            'driver' => 'log',
                        ],
                        'pusher' => [
                            'driver' => 'pusher',
                            'routes' => \Nuwave\Lighthouse\Subscriptions\SubscriptionRouter::class.'@pusher',
                            'connection' => 'pusher',
                        ],
                    ],
                ],
            ],
        ],

        'mll-lab/laravel-graphql-playground' => [
            // Service providers to be registered by your plugin
            'providers' => [
                'Nuwave\Lighthouse\LighthouseServiceProvider',
            ],

            // Aliases to be registered by your plugin in the form of $alias => $pathToFacade
            'aliases' => [],

            // The namespace to set the configuration under. For this example, this package accesses it's config via config('purifier.' . $key), so the namespace 'purifier' is what we put here
            'config_namespace' => 'graphql-playground',

            // The configuration file for the package itself. Start this out by copying the default one that comes with the package and then modifying what you need.
            'config' => [
                /*
                |--------------------------------------------------------------------------
                | GraphQL Playground route
                |--------------------------------------------------------------------------
                |
                | Set the route to which the GraphQL Playground responds.
                | The default endpoint is "yourdomain.com/graphql-playground".
                |
                */
                'route_name' => 'graphql-playground',
                /*
                |--------------------------------------------------------------------------
                | Route configuration
                |--------------------------------------------------------------------------
                |
                | Additional configuration for the route group https://lumen.laravel.com/docs/routing#route-groups
                |
                | For domain config it could be something like:
                | 'domain' => 'graphql.' . env('APP_DOMAIN', 'localhost'),
                |
                */
                'route' => [
                    // 'prefix' => '',
                    // 'middleware' => ['web']
                    'middleware' => ['web', 'api-checkauth'],
                    'domain' => env('GRAPHQL_PLAYGROUND_DOMAIN', null),
                ],
                /*
                |--------------------------------------------------------------------------
                | Default GraphQL endpoint
                |--------------------------------------------------------------------------
                |
                | The default endpoint that the Playground UI is set to.
                | It assumes you are running GraphQL from the same Laravel instance,
                | but can be set to any URL.
                |
                */
                'endpoint' => 'graphql',
                /*
                |--------------------------------------------------------------------------
                | Control Playground availability
                |--------------------------------------------------------------------------
                |
                | Control if the playground is accessible at all.
                | This allows you to disable it in certain environments,
                | for example you might not want it active in production.
                |
                */
                'enabled' => env('GRAPHQL_PLAYGROUND_ENABLED', true),
            ],
        ],
    ],
];
