<?php

namespace Modules\Users\app\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersExeption extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }
    
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        //
    }
}
