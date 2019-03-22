<?php

namespace App\Http\Middleware;

use Closure;
use App\JwtModel;
use App\User;

class JwtMiddleware
{
	private $jwtModel;

	public function __construct(){
		$this->jwtModel = new JwtModel();
	}

	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$token = $request->input('token');

    	if (!$token) {
    		return response()->json([
    			'code' => 400,
				'message' => 'token is empty'
			]);
		}
		try{

    		$result = $this->jwtModel->getDecodeJwt($token);

		} catch (\Exception $exception) {

    		return response()->json([
    			'code' => 500,
				'message' => 'server error'
			]);

		}
		$user = User::find($result->id);

    	$request->auth = $user;

        return $next($request);
    }
}
