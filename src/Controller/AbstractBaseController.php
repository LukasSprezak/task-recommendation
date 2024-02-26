<?php

declare(strict_types=1);

namespace App\Controller;

use App\Provider\ResponseProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractBaseController
{
    protected function json(ResponseProvider $responseProvider): JsonResponse
    {
        return new JsonResponse(
            data: $responseProvider->createResponse(),
            status: $responseProvider->status
        );
    }
}
