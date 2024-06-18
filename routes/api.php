<?php

//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Str;
//use Orion\Facades\Orion;

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

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

//Route::middleware(
//    [
//        'api',
//        InitializeTenancyByDomain::class,
//        PreventAccessFromCentralDomains::class,
//        SubstituteBindings::class,
//    ],
//)->group(
//    function ()
//    {
//        Route::middleware(['auth:sanctum'])->prefix('v1')->group(
//            function ()
//            {
//                Route::group(
//                    ['as' => 'api.'],
//                    function ()
//                    {
//                        // Percorre cada diretório na pasta Modules
//                        foreach (glob(base_path('Modules') . '/*', GLOB_ONLYDIR) as $moduleDir) {
//                            $moduleName = basename($moduleDir);
//
//                            // Percorre cada arquivo PHP no diretório Http/Controllers do módulo
//                            foreach (glob($moduleDir . '/app/Http/Controllers/*.php') as $filename) {
//                                if (basename($filename, '.php') === 'Controller') {
//                                    continue;
//                                }
//
//                                $classNameWithPath = 'Modules\\' . $moduleName . '\\app\\Http\\Controllers\\' . basename($filename, '.php');
//
//                                if (class_exists($classNameWithPath)) {
//                                    $reflector = new ReflectionClass($classNameWithPath);
//                                    if (!$reflector->isAbstract() && $reflector->isSubclassOf(App\Http\Controllers\Controller::class)) {
//                                        if ($reflector->hasProperty('model')) {
//                                            // Define a rota com base no nome do controlador
//                                            $baseRouteName = Str::lower(str_replace('Controller', '', basename($filename, '.php')));
//                                            Orion::resource($baseRouteName, $classNameWithPath)->withSoftDeletes();
//                                        }
//                                    }
//                                }
//                            }
//                        }
//
//                        // Percorre cada arquivo PHP no diretório app/Http/Controllers
//                        foreach (glob(base_path('app/Http/Controllers/*.php')) as $filename) {
//                            if (basename($filename, '.php') === 'Controller') {
//                                continue;
//                            }
//
//                            $classNameWithPath = 'App\\Http\\Controllers\\' . basename($filename, '.php');
//
//                            if (class_exists($classNameWithPath)) {
//                                $reflector = new ReflectionClass($classNameWithPath);
//                                if (!$reflector->isAbstract() && $reflector->isSubclassOf(App\Http\Controllers\Controller::class)) {
//                                    if ($reflector->hasProperty('model')) {
//                                        // Define a rota com base no nome do controlador
//                                        $baseRouteName = Str::lower(str_replace('Controller', '', basename($filename, '.php')));
//                                        Orion::resource($baseRouteName, $classNameWithPath)->withSoftDeletes();
//                                    }
//                                }
//                            }
//                        }
//                    },
//                );
//            },
//        );
//    },
//);

use Modules\AccessControl\app\Actions\LoginAction;

Route::post(uri: '/login', action: LoginAction::class);
