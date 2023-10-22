<?php

namespace Domain\DTO;

class VehicleDTO
{
    public ?int $id;
    public string $registrationNumber;
    public string $brand;
    public string $model;
    public string $type;
    public string $createdAt;
    public string $updatedAt;
}
