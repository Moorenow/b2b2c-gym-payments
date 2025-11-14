<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    /**
     * Return summary data to manage roles and permissions.
     */
    public function index(): JsonResponse
    {
        $roles = Role::with('permissions:id,name')->get(['id', 'name', 'guard_name']);
        $permissions = Permission::get(['id', 'name', 'guard_name']);
        $users = User::with('roles:id,name')->get(['id', 'name', 'email']);

        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions,
            'users' => $users,
        ]);
    }

    /**
     * Allow admins to assign a role to the selected user.
     */
    public function updateUserRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => [
                'required',
                'string',
                Rule::in([User::ROLE_SUPER_ADMIN, User::ROLE_GYM_ADMIN]),
            ],
        ]);

        $roleName = $validated['role'];

        $user->syncRoles([$roleName]);

        return response()->json([
            'message' => 'Rol actualizado correctamente.',
            'user' => $user->fresh('roles'),
        ]);
    }
}
