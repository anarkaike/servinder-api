<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Orion\Facades\Orion;

/*
 * --------------------------------------------------------------------------
 * API Routes
 * --------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
 */
Route::middleware(['auth:sanctum'])->prefix('v1')->group(
    function ()
    {
        Route::group(
            ['as' => 'api.'],
            function ()
            {
                // Percorre cada diretório na pasta Modules
                foreach (glob(base_path('Modules') . '/*', GLOB_ONLYDIR) as $moduleDir) {
                    $moduleName = basename($moduleDir);
                    
                    // Percorre cada arquivo PHP no diretório Http/Controllers do módulo
                    foreach (glob($moduleDir . '/app/Http/Controllers/*.php') as $filename) {
                        $classNameWithPath = 'Modules\\' . $moduleName . '\\app\\Http\\Controllers\\' . basename($filename, '.php');
                        
                        if (class_exists($classNameWithPath)) {
                            $reflector = new ReflectionClass($classNameWithPath);
                            if (!$reflector->isAbstract() && $reflector->isSubclassOf(App\Http\Controllers\Controller::class)) {
                                // Define a rota com base no nome do controlador
                                $baseRouteName = Str::lower($moduleName);
                                Orion::resource($baseRouteName, $classNameWithPath)->withSoftDeletes();
                            }
                        }
                    }
                }
            },
        );
    },
);
