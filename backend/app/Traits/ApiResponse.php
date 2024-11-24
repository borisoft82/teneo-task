<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse {

    protected function success($data, int $statusCode = 200): JsonResponse {
        return response()->json($data, $statusCode);
    }

    protected function error($data = []): JsonResponse {
        return response()->json($data);
    }
    
}