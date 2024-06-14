<?php

namespace Modules\Users\app\Http\Controllers;

use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfilesController extends ApiController
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Profile::class;
}
