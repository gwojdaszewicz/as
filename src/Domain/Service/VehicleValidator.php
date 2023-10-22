<?php

namespace Domain\Service;

use Domain\DTO\VehicleDTO;

class VehicleValidator
{
    private array $requiredFields = [
        'registrationNumber',
        'brand',
        'model',
        'type',
    ];

    public function validate(VehicleDTO $dto): array
    {
        $errors = [];

        foreach ($this->requiredFields as $field) {
            if (empty($dto->{$field})) {
                $errors[$field] = ucfirst($field) . ' is required.';
            }
        }

        return $errors;
    }
}
