<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login', 'register']]);
    }
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'status' => 'required|boolean',
            'role' => 'string'
        ]);
        try {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->status = $request->input('status');
            $user->role = $request->input('role');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();
            return response()->json(['message' => 'User Created Succesfully'], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        try {
            $credentials = $request->only(['email', 'password']);
            if (!$token = Auth::attempt($credentials)) {
                // Login has failed
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(), "code" => $e->getCode()], 400);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logged Out Successfully'], 200);
    }

    public function refresh()
    {
        $newToken = auth()->refresh(true, true);
        return $this->respondWithToken($newToken);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::factory()->getTTL() * 1,
            'unit' => 'minutes',
        ], 200);
    }
}
