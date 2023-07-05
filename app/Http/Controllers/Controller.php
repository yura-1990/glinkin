<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success($data): JsonResponse
    {
        return response()->json($data);
    }

    public function error($errors, string $message = '', int $code = 400): JsonResponse
    {
        return response()->json([
            'errors' => $errors,
            'message' => $message
        ], $code);
    }
}
