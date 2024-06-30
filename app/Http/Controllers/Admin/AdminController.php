<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\UserRoleRequest;
use App\Models\User;
use App\Models\UserRole;
use App\Services\DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    private $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function createUser(RegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $userData['user_role'] = json_encode($userData['user_role']);
        $userData['password'] = Hash::make($userData['password']);

        $user = $this->dataService->createData('User', $userData);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function createRole(RoleRequest $request): JsonResponse
    {
        $roleData = $request->validated();
        $data = $this->dataService->createData('Role', $roleData);
        return jsonResponse('Role created', $data, Response::HTTP_CREATED);
    }

    /**
     * @throws \Exception
     */
    public function getRoles(): JsonResponse
    {
        $data = $this->dataService->getModelData('Role');
        return jsonResponse('Role fetched', $data, Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function createUserRole(UserRoleRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $userRole = UserRole::create($validatedData);

        // Find the user by ID
        $user = User::findOrFail($userRole->user_id);

        // Decode the existing user_role JSON column
        $userRoles = $user->user_role ?? [];

        // Add the new role_id to the user_role array
        if (!in_array($userRole->role_id, $userRoles)) {
            $userRoles[] = $userRole->role_id;
        }

        // Update the user_role JSON column
        $user->user_role = $userRoles;
        $user->save();
        return jsonResponse('User role created', $userRole, Response::HTTP_CREATED);
    }

    /**
     * @throws \Exception
     */
    public function getUserRoles(): JsonResponse
    {
//        $data = $this->dataService->getModelData('UserRole');
        $data = UserRole::with('roles')->get();
        return jsonResponse('User roles fetched', $data, Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function getRolesByUserId($userId): JsonResponse
    {
//        rework on this logic
//        Summary endpoint
        $data = $this->dataService->getModelById('UserRole', $userId);
        return jsonResponse('User role fetched', $data, Response::HTTP_OK);
    }

    public function getUsers()
    {
        $data = User::with('roles')->get();
        return jsonResponse('Users fetched', $data, Response::HTTP_OK);
    }

    public function getUsersByRole()
    {

    }

    public function getUsersById()
    {

    }

}
