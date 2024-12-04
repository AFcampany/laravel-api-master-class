<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Login
     *
     * authenticate the user and returns user's token API
     *
     * @unauthenticated
     * @group Authentication
     * @response 200 {
            "data": {
                "token": "{YOUR_AUTH_KEY}"
            },
            "message": "Authenticated",
            "status": 200
        }
     */
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalide credentials', 401);
        }

        /**
         * @var \App\Models\User $user
         */
        $user = User::firstWhere('email', $request->email);

        return $this->ok(
            "Authenticated",
            [
                'token' => $user->createToken(
                    "api-token-for-$user->email",
                    Abilities::getAbilities($user),
                    now()->addMonth(),
                )->plainTextToken,
            ]
        );
    }

    /**
     * Logout
     *
     * Signs out the user and destroy's the API Token.
     *
     * @group Authentication
     * @response 200 {}
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('');
    }

    public function register()
    {
        // return $this->ok('register');
    }
}
