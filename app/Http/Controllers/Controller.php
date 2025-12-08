<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Log action untuk audit
     */
    protected function logAction($action, $modelType, $modelId, $oldValues = null, $newValues = null)
    {
        try {
            \App\Models\AuditLog::log($action, $modelType, $modelId, $oldValues, $newValues);
        } catch (\Exception $e) {
            Log::error('Audit log failed: ' . $e->getMessage());
        }
    }

    /**
     * Respons sukses dengan pesan
     */
    protected function successResponse($message, $data = null, $redirect = null)
    {
        if ($redirect) {
            return redirect($redirect)->with('success', $message);
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => 'success'
        ], 200);
    }

    /**
     * Respons error dengan pesan
     */
    protected function errorResponse($message, $errors = null, $statusCode = 400)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => $errors,
                'status' => 'error'
            ], $statusCode);
        }

        return back()->with('error', $message)->withInput();
    }

    /**
     * Handle model not found dengan graceful
     */
    protected function handleNotFound($model, $message = null)
    {
        $defaultMessage = $model . ' tidak ditemukan.';
        
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message ?? $defaultMessage,
                'status' => 'error'
            ], 404);
        }

        return back()->with('error', $message ?? $defaultMessage);
    }
}
