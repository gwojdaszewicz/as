<?php

namespace Domain\Entity;

use Domain\ValueObject\RegistrationNumber;
use Domain\ValueObject\VehicleType;

final class Vehicle
{
    public function __construct(
        private readonly RegistrationNumber $registrationNumber,
        private readonly string $brand,
        private readonly string $model,
        private readonly VehicleType $type,
        private readonly \DateTime $createdAt,
        private readonly \DateTime $updatedAt,
        private ?int $id = null
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getType(): VehicleType
    {
        return $this->type;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
