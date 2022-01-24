<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;

class AuthController extends Controller
{
    const FAILED_ATTEMPT_COUNT = 5;

    public function __construct() {
        $this->middleware('api', ['except' => 'login', 'register']);
    }

    public function login(Request $request) {
        $input = $request->json()->all();

		$email = $input['email'];
		$password = $input['password'];

		$carbon = Carbon::now();

		$user = User::where('email', $email)->first();

		$userLocked = $carbon->lte($user->locked);
		if($userLocked) {
			return response()->json([
				'message' => "Account locked",
                'until' => $user->locked
			], 403);
		}

		$hashedPassword = Hash::make($password);
		$token = $this->guard()->attempt([
            'email' => $email,
            'password' => $password
        ]);
		if (!$token) {
			$failedAttempts = ($user->failed_attempts ? $user->failed_attempts : 0) + 1;
			$user->failed_attempts = $failedAttempts;

			$message = 'Invalid credentials';
			$code = 401;
			if($failedAttempts >= self::FAILED_ATTEMPT_COUNT) {
				$timeout = $carbon->addMinutes(5);
				$user->locked = $timeout;
				$user->failed_attempts = 0;
				
				$message = 'Invalid credentials. Account locked for 5 minutes';
			}
			$user->save();
			
            return response()->json([
				'message' => $message,
			], $code);
        }

        return response()->json([
			'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
		], 201);
    }
    /**
	 * User registration
	 */
    public function register(Request $request) {
        $input = $request->json()->all();
		
		$email = $input['email'];
		$password = $input['password'];
			
		//Check if user exists
		$userExists = User::where('email', $email)->first();
		if($userExists) {
			return response()->json([
				'message' => 'Email already taken'
			], 400);
		}
			
		$hashedPassword = Hash::make($password);

		//Create user if it doesn't exist.
		$user = User::create([
			'email' => $email,
			'password' => $hashedPassword,
		]);
			
		return response()->json([
			'message' => 'User successfully registered',
		], 201);
    }

    protected function guard() {
        return Auth::guard();
    }
}
