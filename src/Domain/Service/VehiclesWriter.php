<?php

namespace Domain\Service;

use DateTimeInterface;
use Domain\DTO\VehicleDTO;
use Domain\Repository\VehicleRepositoryInterface;

class VehiclesWriter
{
    public function __construct(
        private readonly VehicleRepositoryInterface $vehicleRepository,
        private readonly VehiclesBuilder $vehiclesBuilder,
        private readonly VehicleValidator $vehicleValidator,
    ) {
    }

    public function saveVehicle(VehicleDTO $vehicleDTO): array
    {
        $errors = $this->vehicleValidator->validate($vehicleDTO);
        if (count($errors) > 0) {
            return ['success' => false, 'errors' => $errors];
        }

        $now = (new \DateTime())->format(DateTimeInterface::ATOM);
        if (empty($vehicleDTO->id)) {
            $vehicleDTO->createdAt = $now;
        }
        $vehicleDTO->updatedAt = $now;

        $vehicle = $this->vehiclesBuilder->dtoToEntity($vehicleDTO);
        $updatedVehicle = $this->vehicleRepository->persist($vehicle);
        $vehicleDTO = $this->vehiclesBuilder->entityToDto($updatedVehicle);

        return ['success' => true, 'vehicle' => $vehicleDTO];
    }

    public function deleteById(int $id): void
    {
        $this->vehicleRepository->deleteById($id);
    }
}

