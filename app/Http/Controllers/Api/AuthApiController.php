<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends BaseApiController
{
    /**
     * Login e geração de token
     */
    public function login(Request $request)
    {
        try {
            $validated = $this->validateRequest($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return $this->error('Credenciais inválidas', 401);
            }

            if (!$user->active) {
                return $this->error('Usuário inativo', 403);
            }

            // Criar token Sanctum
            $token = $user->createToken('api-token', ['*'])->plainTextToken;

            return $this->success([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Login realizado com sucesso');
        } catch (ValidationException $e) {
            return $this->validationError($e->errors());
        } catch (\Exception $e) {
            return $this->error('Erro ao realizar login: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Logout e revogação de token
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->success(null, 'Logout realizado com sucesso');
        } catch (\Exception $e) {
            return $this->error('Erro ao realizar logout: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Informações do usuário autenticado
     */
    public function me(Request $request)
    {
        try {
            $user = $request->user();

            return $this->success([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ], 'Dados do usuário recuperados com sucesso');
        } catch (\Exception $e) {
            return $this->error('Erro ao recuperar dados: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Revogar todos os tokens do usuário
     */
    public function revokeAllTokens(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return $this->success(null, 'Todos os tokens foram revogados com sucesso');
        } catch (\Exception $e) {
            return $this->error('Erro ao revogar tokens: ' . $e->getMessage(), 500);
        }
    }
}

