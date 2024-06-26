<?php

use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Providers\ConsoleServiceProvider;

return [
    
    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace'  => 'Modules',
    
    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */
    'stubs'      => [
        'enabled'      => FALSE,
        'path'         => base_path('vendor/nwidart/laravel-modules/src/Commands/stubs'),
        'files'        => [
            'routes/web'      => 'routes/web.php',
            'routes/api'      => 'routes/api.php',
            'views/index'     => 'resources/views/index.blade.php',
            'views/master'    => 'resources/views/layouts/master.blade.php',
            'scaffold/config' => 'config/config.php',
            'composer'        => 'composer.json',
            'assets/js/app'   => 'resources/assets/js/app.js',
            'assets/sass/app' => 'resources/assets/sass/app.scss',
            'vite'            => 'vite.config.js',
            'package'         => 'package.json',
        ],
        'replacements' => [
            'routes/web'      => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'routes/api'      => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'vite'            => ['LOWER_NAME', 'STUDLY_NAME'],
            'json'            => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/index'     => ['LOWER_NAME'],
            'views/master'    => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer'        => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
                'APP_FOLDER_NAME',
            ],
        ],
        'gitkeep'      => TRUE,
    ],
    'paths'      => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'modules'    => base_path('Modules'),
        
        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules' assets path.
        |
        */
        'assets'     => public_path('modules'),
        
        /*
        |--------------------------------------------------------------------------
        | The migrations' path
        |--------------------------------------------------------------------------
        |
        | Where you run the 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration'  => base_path('database/migrations'),
        
        /*
        |--------------------------------------------------------------------------
        | The app path
        |--------------------------------------------------------------------------
        |
        | app folder name
        | for example can change it to 'src' or 'App'
        */
        'app_folder' => 'app/',
        
        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        */
        'generator'  => [
            // app/
            'actions'         => ['path' => 'app/Actions', 'generate' => TRUE],
            'casts'           => ['path' => 'app/Casts', 'generate' => TRUE],
            'channels'        => ['path' => 'app/Broadcasting', 'generate' => TRUE],
            'command'         => ['path' => 'app/Console', 'generate' => TRUE],
            'component-class' => ['path' => 'app/View/Components', 'generate' => TRUE],
            'emails'          => ['path' => 'app/Emails', 'generate' => TRUE],
            'event'           => ['path' => 'app/Events', 'generate' => TRUE],
            'enums'           => ['path' => 'app/Enums', 'generate' => TRUE],
            'exceptions'      => ['path' => 'app/Exceptions', 'generate' => TRUE],
            'jobs'            => ['path' => 'app/Jobs', 'generate' => TRUE],
            'helpers'         => ['path' => 'app/Helpers', 'generate' => TRUE],
            'interfaces'      => ['path' => 'app/Interfaces', 'generate' => TRUE],
            'listener'        => ['path' => 'app/Listeners', 'generate' => TRUE],
            'model'           => ['path' => 'app/Models', 'generate' => TRUE],
            'notifications'   => ['path' => 'app/Notifications', 'generate' => TRUE],
            'observer'        => ['path' => 'app/Observers', 'generate' => TRUE],
            'policies'        => ['path' => 'app/Policies', 'generate' => TRUE],
            'provider'        => ['path' => 'app/Providers', 'generate' => TRUE],
            'repository'      => ['path' => 'app/Repositories', 'generate' => TRUE],
            'resource'        => ['path' => 'app/Transformers', 'generate' => TRUE],
            'route-provider'  => ['path' => 'app/Providers', 'generate' => TRUE],
            'rules'           => ['path' => 'app/Rules', 'generate' => TRUE],
            'services'        => ['path' => 'app/Services', 'generate' => TRUE],
            'scopes'          => ['path' => 'app/Models/Scopes', 'generate' => TRUE],
            'traits'          => ['path' => 'app/Traits', 'generate' => TRUE],
            
            // app/Http/
            'controller'      => ['path' => 'app/Http/Controllers', 'generate' => TRUE],
            'filter'          => ['path' => 'app/Http/Middleware', 'generate' => TRUE],
            'request'         => ['path' => 'app/Http/Requests', 'generate' => TRUE],
            
            // config/
            'config'          => ['path' => 'config', 'generate' => TRUE],
            
            // database/
            'factory'         => ['path' => 'database/factories', 'generate' => TRUE],
            'migration'       => ['path' => 'database/migrations', 'generate' => TRUE],
            'seeder'          => ['path' => 'database/seeders', 'generate' => TRUE],
            
            // lang/
            'lang'            => ['path' => 'lang', 'generate' => TRUE],
            
            // resource/
            'assets'          => ['path' => 'resources/assets', 'generate' => TRUE],
            'component-view'  => ['path' => 'resources/views/components', 'generate' => TRUE],
            'views'           => ['path' => 'resources/views', 'generate' => TRUE],
            
            // routes/
            'routes'          => ['path' => 'routes', 'generate' => TRUE],
            
            // tests/
            'test-feature'    => ['path' => 'tests/Feature', 'generate' => TRUE],
            'test-unit'       => ['path' => 'tests/Unit', 'generate' => TRUE],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. You can add your own commands to merge section.
    |
    */
    'commands'   => ConsoleServiceProvider::defaultCommands()
                                          ->merge(
                                              [
                                                  // New commands go here
                                              ],
                                          )->toArray(),
    
    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */
    'scan'       => [
        'enabled' => TRUE,
        'paths'   => [
            base_path('vendor/*/*'),
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for the composer.json file, generated by this package
    |
    */
    'composer'   => [
        'vendor'          => env('MODULE_VENDOR', 'nwidart'),
        'author'          => [
            'name'  => env('MODULE_AUTHOR_NAME', 'Nicolas Widart'),
            'email' => env('MODULE_AUTHOR_EMAIL', 'n.widart@gmail.com'),
        ],
        'composer-output' => TRUE,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up the caching feature.
    |
    */
    'cache'      => [
        'enabled'  => env('MODULES_CACHE_ENABLED', FALSE),
        'driver'   => env('MODULES_CACHE_DRIVER', 'file'),
        'key'      => env('MODULES_CACHE_KEY', 'laravel-modules'),
        'lifetime' => env('MODULES_CACHE_LIFETIME', 60),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register'   => [
        'translations' => TRUE,
        /**
         * load files on boot or register method
         */
        'files'        => 'register',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database, etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */
    'activators' => [
        'file' => [
            'class'          => FileActivator::class,
            'statuses-file'  => base_path('modules_statuses.json'),
            'cache-key'      => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],
    
    'activator' => 'file',
];
