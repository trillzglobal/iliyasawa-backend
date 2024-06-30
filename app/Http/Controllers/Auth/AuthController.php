<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $dataService;

    public function __construct(DataService $dataService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->dataService = $dataService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token = Auth::attempt($credentials)) {
            return jsonResponse('Unauthorized', null, Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $user->active_role = $user->user_role[0];
        $user->save();

        return jsonResponse('Login success', ['user' => $user, 'token' => $token], Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $userData['user_role'] = json_encode($userData['user_role']);
        $userData['password'] = Hash::make($userData['password']);
        $userData['active_role'] = $userData['user_role'][0];

        $user = $this->dataService->createData('User', $userData);

        $token = Auth::login($user);
        return jsonResponse('User created successfully', ['user' => $user, 'authorisation' => ['token' => $token, 'type' => 'bearer',]], Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return jsonResponse('Successfully logged out', null, Response::HTTP_OK);
    }

    public function refresh(): JsonResponse
    {
        return jsonResponse('Token refreshed', ['user' => Auth::user(), 'authorisation' => ['token' => Auth::refresh(), 'type' => 'bearer',]], Response::HTTP_OK);
    }

    public function switchRole(Request $request): JsonResponse
    {
        $request->validate([
            'role_id' => 'required|integer',
        ]);

        $user = Auth::user();

        if (!in_array($request->role_id, $user->user_role)) {
            return jsonResponse('Invalid role| Role not found', [], Response::HTTP_BAD_REQUEST);
        }

        $user->active_role = $request->role_id;
        $user->save();

        // Generate a new token with updated claims
        $token = Auth::refresh();

        return jsonResponse('Role switching success', ['user' => $user, 'token' => $token, 'type' => 'bearer'], Response::HTTP_ACCEPTED);
    }

}
