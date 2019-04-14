<?php

namespace App\Exceptions\Formatters;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Optimus\Heimdal\Formatters\BaseFormatter;

/**
 * @package App\Exceptions\Formatters
 */
class UnauthorizedHttpExceptionFormatter extends BaseFormatter
{
    /**
     * @param JsonResponse $response
     * @param Exception $e
     * @param array $reporterResponses
     */
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        $response
            ->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->setData([
                'errors' => [
                    "title" => "Unauthorized",
                    "status" => Response::HTTP_UNAUTHORIZED
                ]
            ]);
    }
}