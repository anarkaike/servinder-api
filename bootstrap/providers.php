<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\CommandsServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    Barryvdh\Debugbar\ServiceProvider::class,
    OwenIt\Auditing\AuditingServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
    Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
];
