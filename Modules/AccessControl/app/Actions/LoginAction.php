<?php

namespace Modules\AccessControl\app\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    use AsAction;
    
    public function handle(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json(
                [
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                ],
            );
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
