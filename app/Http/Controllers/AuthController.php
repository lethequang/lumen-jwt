<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
	public $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request) {

    	$this->validate($request, [
    		'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
		]);

		$user = $this->user->create([
			'name' => $request->get('name'),
			'email' => $request->get('email'),
			'password' => Hash::make($request->get('password'))
		]);

    	return response()->json([
			'code' => 200,
			'message' => 'register successfully',
			'data' => $user
		]);
	}

	public function login(Request $request) {
		$this->validate($request, [
			'email'    => 'required',
			'password' => 'required'
		]);

		$result = $this->user->handleLogin($request->all());

		if(!$result) {
			return response()->json([
				'code' => 400,
				'message' => 'invalid'
			]);
		}

		return response()->json([
			'code' => 200,
			'message' => 'success',
			'token' => $result
		]);
	}

	public function getInfo() {
    	$result = $this->user->showAll();
    	return response()->json($result);
	}
}
