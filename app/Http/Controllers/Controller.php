<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function respondSuccess(Request $request, string $message, ?string $redirect = null, array $payload = [])
    {
        if ($request->expectsJson()) {
            return response()->json(array_merge(['message' => $message], $payload));
        }

        return redirect($redirect ?? url()->previous())->with('message', $message);
    }

    protected function respondError(Request $request, string $message, int $status = 422, ?string $redirect = null)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], $status);
        }

        return redirect($redirect ?? url()->previous())->with('error', $message);
    }
}
