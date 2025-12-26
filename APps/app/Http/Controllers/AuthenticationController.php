<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    /**
     * Return authenticated user info
     */
    public function userInfo(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Log out the current token or all tokens
     */
    public function logOut(Request $request)
    {
        $user = $request->user();
        if ($user) {
            // Delete the current access token if present, otherwise revoke all
            if ($request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            } else {
                $request->user()->tokens()->delete();
            }
        }

        return response()->json(['message' => 'Logged out'], 200);
    }
}