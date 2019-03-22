<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function showAll() {
    	return User::all();
	}

	public function handleLogin($params = array()) {
		$user = User::where('email', $params['email'])->first();
		if (!$user) {
			return null;
		}
		if(!Hash::check($params['password'], $user->password)) {
			return null;
		}

		return $this->generateToken($user);
	}

	public function generateToken($data) {
		$jwtModel = new JwtModel();
		return $jwtModel->getEncodeJwt($data);
	}
}
