<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signin() {
        return view('signin');
    }

    public function signin_action(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'     => 'required',
                'password'  => 'required'
            ]);

            $remember = isset($request->remember) ? filter_var($request->remember, FILTER_VALIDATE_BOOLEAN) : false;

            if(Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                $request->user()->tokens()->delete();
                $request->session()->put('auth_token', $token = $request->user()->createToken('authToken')->plainTextToken);
                $request->session()->put('role', $request->user()->role);

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'user' => $request->user(),
                    'token' => $token,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong email or password!',
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong email or password!',
                'errors' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function signout(Request $request)
    {
        $request->user()->tokens()->where('tokenable_id', Auth::user()->id)->delete();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.signin');
    }
}
