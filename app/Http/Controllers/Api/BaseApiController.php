<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class BaseApiController extends Controller
{
    /**
     * Retorna resposta de sucesso padronizada
     */
    protected function success($data = null, string $message = 'Operação realizada com sucesso', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Retorna resposta de erro padronizada
     */
    protected function error(string $message = 'Erro ao processar requisição', int $code = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Retorna resposta de validação
     */
    protected function validationError(array $errors, string $message = 'Dados inválidos'): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Valida dados da requisição
     */
    protected function validateRequest(Request $request, array $rules): array
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Retorna dados paginados padronizados
     */
    protected function paginated($paginator, string $message = 'Dados recuperados com sucesso'): JsonResponse
    {
        return $this->success([
            'items' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ], $message);
    }

    /**
     * Retorna resposta de não autorizado
     */
    protected function unauthorized(string $message = 'Não autorizado'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Retorna resposta de não encontrado
     */
    protected function notFound(string $message = 'Recurso não encontrado'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Retorna resposta de proibido
     */
    protected function forbidden(string $message = 'Acesso negado'): JsonResponse
    {
        return $this->error($message, 403);
    }
}

