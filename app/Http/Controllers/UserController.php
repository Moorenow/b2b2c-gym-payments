<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return the authenticated user profile with role data.
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('roles'));
    }
}
