<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users with their roles
     * Only admin can access this
     */
    public function index(): JsonResponse
    {
        // Check if user is admin
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Only administrators can view all users.'
            ], 403);
        }

        $users = User::with(['roles', 'permissions'])->get();
        
        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                    'created_at' => $user->created_at,
                ];
            })
        ]);
    }

    /**
     * Show specific user with roles
     */
    public function show(User $user): JsonResponse
    {
        // Only admin or the user themselves can view user details
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $user->id) {
            return response()->json([
                'message' => 'You can only view your own profile.'
            ], 403);
        }

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'created_at' => $user->created_at,
            ]
        ]);
    }

    /**
     * Assign role to user
     * Only admin can assign roles
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        // Check if user is admin
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Only administrators can assign roles.'
            ], 403);
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Role assigned successfully',
            'data' => [
                'user' => $user->name,
                'assigned_role' => $request->role,
                'current_roles' => $user->roles->pluck('name'),
            ]
        ]);
    }

    /**
     * Remove role from user
     * Only admin can remove roles
     */
    public function removeRole(Request $request, User $user): JsonResponse
    {
        // Check if user is admin
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Only administrators can remove roles.'
            ], 403);
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user->removeRole($request->role);

        return response()->json([
            'message' => 'Role removed successfully',
            'data' => [
                'user' => $user->name,
                'removed_role' => $request->role,
                'current_roles' => $user->roles->pluck('name'),
            ]
        ]);
    }

    /**
     * Get all available roles
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::with('permissions')->get();
        
        return response()->json([
            'message' => 'Roles retrieved successfully',
            'data' => $roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                ];
            })
        ]);
    }
}