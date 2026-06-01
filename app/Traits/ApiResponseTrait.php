<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Return a success JSON response.
     */
    protected function successResponse(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'messages' => [$message],
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(string $message, int $statusCode = 400, $errors = null): JsonResponse
    {
        $messages = [$message];

        if ($errors !== null) {
            if (is_array($errors)) {
                foreach ($errors as $fieldErrors) {
                    if (is_array($fieldErrors)) {
                        $messages = array_merge($messages, $fieldErrors);
                    } else {
                        $messages[] = $fieldErrors;
                    }
                }
            } else {
                $messages[] = $errors;
            }
        }

        $response = [
            'success' => false,
            'messages' => array_values(array_unique($messages)),
        ];

        return response()->json($response, $statusCode);
    }
}
