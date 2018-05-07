<?php

namespace App\Http\Middleware;

use App\Merchant;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class AuthMerchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check basic auth user and password
        if ($request->getUser() === null || $request->getPassword() === null) {
            return response()->json([
                'status' => 'bad request',
                'code' => 4000,
                'reason' => 'authorization headers not properly set'
            ], 400);
        }

        $merchant = Merchant::where('api_user', $request->getUser())->where('api_key', $request->getPassword())->first();

        if ($merchant) {
            if ($merchant->is_active) {
                return $next($request);
            }

            return response()->json([
                'status' => 'unauthorized',
                'code' => 4000,
                'reason' => 'merchant account deactivated'
            ], 401);

        }

        return response()->json([
            'status' => 'unauthorized',
            'code' => 4000,
            'reason' => 'invalid merchant credentials'
        ], 401);

    }
}
