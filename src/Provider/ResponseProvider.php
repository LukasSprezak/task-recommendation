<?php

declare(strict_types=1);

namespace App\Provider;

use Symfony\Component\HttpFoundation\Response;

final class ResponseProvider
{
    public function __construct(
        public int $status = Response::HTTP_OK,
        public string|null $message = null,
        public array|null $data = null,
        public array|null $error = null
    ) {
    }

    public function createResponse(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'error' => $this->error
        ];
    }
}
