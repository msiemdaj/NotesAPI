<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success($data=[], $message=null, $code=200, $paginator=null): JsonResponse
    {
        $aResponse = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if ($paginator !== null) {
            $aResponse['pagination'] = [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ];
        }

        return response()->json($aResponse, $code);
    }

    protected function error($error=[], $message=null, $code=400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $error,
        ], $code);
    }
}
