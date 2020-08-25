<?php

namespace App\Http\Middleware\User;

use Closure;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authentication
{
   /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Auth::shouldUse('user');
        $response = array(
            'status' => false,
            'message' => 'Authorization token is invalid'
        );
        if ($request->hasHeader('Authorization')) {
            try {
                /** @var array $token */
                $token = decrypt($request->header('Authorization'));
            } catch (\Exception $e) {
                return response()->json($response, 401);
            }

            if (
                isset($token['user_id']) &&
                isset($token['user_type']) &&
                $token['user_type'] == User::class &&
                $user = User::find($token['user_id'])
            ) {
                \Auth::login($user);
            } else {
                return response()->json($response, 401);
            }
        } else {
            return response()->json($response, 401);
        }

        return $next($request);
    }

}
