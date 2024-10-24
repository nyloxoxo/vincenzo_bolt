<?php

namespace App\Services;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class HealthcareApiResponse implements Arrayable, JsonSerializable
{
    public ?array $data = null;

    public ?int $errorCode = null;
    public ?string $errorMessage = null;

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'errorCode' => $this->errorCode,
            'errorMessage' => $this->errorMessage,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
