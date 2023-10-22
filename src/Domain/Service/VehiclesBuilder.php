<?php

namespace Domain\Service;

use DateTimeInterface;
use Domain\DTO\VehicleDTO;
use Domain\Entity\Vehicle;
use Domain\ValueObject\RegistrationNumber;
use Domain\ValueObject\VehicleType;

class VehiclesBuilder
{
    public function entitiesToDTOs(array $vehicles): array
    {
        return array_map(
            fn(Vehicle $vehicle) => $this->entityToDTO($vehicle),
            $vehicles
        );
    }

    public function entityToDTO(Vehicle $vehicle): VehicleDTO
    {
        $dto = new VehicleDTO();
        $dto->id = $vehicle->getId();
        $dto->registrationNumber = (string)$vehicle->getRegistrationNumber();
        $dto->brand = $vehicle->getBrand();
        $dto->model = $vehicle->getModel();
        $dto->type = $vehicle->getType()->value;
        $dto->createdAt = $vehicle->getCreatedAt()->format(DateTimeInterface::ATOM);
        $dto->updatedAt = $vehicle->getUpdatedAt()->format(DateTimeInterface::ATOM);

        return $dto;
    }

    public function dtoToEntity(VehicleDTO $dto): Vehicle
    {
        return new Vehicle(
            new RegistrationNumber($dto->registrationNumber),
            $dto->brand,
            $dto->model,
            VehicleType::from($dto->type),
            new \DateTime($dto->createdAt),
            new \DateTime($dto->updatedAt),
            $dto->id
        );
    }
}
