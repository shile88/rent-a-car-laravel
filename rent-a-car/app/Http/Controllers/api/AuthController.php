<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request->email, $request->password);
        if(!$token)
            return response(['message' => 'invalid credentials'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        return response(['token' => $token], ResponseAlias::HTTP_OK);
    }

    public function logout()
    {
        $this->authService->logout();
        return response(['message' => 'logged-out'], ResponseAlias::HTTP_OK);
    }
}
