<?php
namespace App\Support;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Exceptions\ApiException;

class ApiResponse
{
    public function success(string $message, mixed $data = [], int $status = 200): JsonResponse
    { return response()->json(['success'=>true,'message'=>$message,'data'=>$data], $status); }

    public function error(string $message, mixed $errors = [], int $status = 400): JsonResponse
    { return response()->json(['success'=>false,'message'=>$message,'errors'=>$errors], $status); }

    public function exception(Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) return $this->error('Validation failed.', $e->errors(), 422);
        if ($e instanceof ApiException) return $this->error($e->getMessage(), $e->errors(), $e->status());
        report($e);
        return $this->error('An unexpected error occurred.', [], 500);
    }
}
