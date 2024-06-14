<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\UserRoleRequest;
use App\Services\DataService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    private $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
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
    public function getRoles(): bool|string
    {
        $data = $this->dataService->getModelData('Role');
        return jsonResponse('Role fetched', $data, Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function createUserRole(UserRoleRequest $request): JsonResponse
    {
        $roleData = $request->validated();
        $data = $this->dataService->createData('UserRole', $roleData);
        return jsonResponse('User role created', $data, Response::HTTP_CREATED);
    }

    /**
     * @throws \Exception
     */
    public function getUserRoles(): bool|string
    {
        $data = $this->dataService->getModelData('UserRole');
        return jsonResponse('User roles fetched', $data, Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function getRolesByUserId($userId): bool|string
    {
//        rework on this logic
        $data = $this->dataService->getModelById('UserRole', $userId);
        return jsonResponse('User role fetched', $data, Response::HTTP_OK);
    }

    public function getUsers()
    {

    }

    public function getUsersByRole()
    {

    }

    public function getUsersById()
    {

    }


}
