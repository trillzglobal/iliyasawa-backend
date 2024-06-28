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
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $dataService;

    public function __construct(DataService $dataService)
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
        $this->dataService = $dataService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        $user->active_role = $user->user_role[0];
        $user->save();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ]);

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
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function switchRole(Request $request): JsonResponse
    {
        $request->validate([
            'role_id' => 'required|integer',
        ]);

        $user = Auth::user();

        if (!in_array($request->role_id, $user->user_role)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid role',
            ], 400);
        }

        $user->active_role = $request->role_id;
        $user->save();

        // Generate a new token with updated claims
        $token = Auth::refresh();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
            'type' => 'bearer',
        ]);
    }

}
