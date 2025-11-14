<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Simple dashboard payload when the authenticated user has access.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Bienvenido al panel de control.',
            'can_manage_tenants' => $user->hasRole($user::ROLE_SUPER_ADMIN),
            'metrics' => [
                'gyms' => 12,
                'active_members' => 420,
                'monthly_revenue' => 12345,
            ],
        ]);
    }
}
