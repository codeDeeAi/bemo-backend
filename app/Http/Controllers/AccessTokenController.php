<?php

namespace App\Http\Controllers;

use App\Models\AccessTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessTokenController extends Controller
{
    // Create New Token
    public function create(Request $request)
    {
        $token = Str::random(33);

        AccessTokens::create([
            'token' => $token
        ]);

        return response()->json([
            'token' => $token
        ], 200);
    }

    // Confirm Token
    public function checkToken(Request $request)
    {
        if (AccessTokens::where('token', $request->query('access_token'))->exists()) {
            return response()->json([
                'token' => $request->query('access_token')
            ], 200);
        }

        return response()->json([
            'message' => 'Bad request !'
        ], 400);
    }
}
