<?php

namespace Domain\Service;

use Domain\DTO\VehicleDTO;
use Domain\Repository\VehicleRepositoryInterface;

class VehiclesReader
{
    private VehicleRepositoryInterface $vehicleRepository;
    private VehiclesBuilder $vehiclesBuilder;

    public function __construct(
        VehicleRepositoryInterface $vehicleRepository,
        VehiclesBuilder $vehiclesBuilder
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehiclesBuilder = $vehiclesBuilder;
    }

    public function getVehicleById(int $id): ?VehicleDTO
    {
        $vehicle = $this->vehicleRepository->getById($id);
        return $vehicle ? $this->vehiclesBuilder->entityToDto($vehicle) : null;
    }

    public function getAllVehicles(): array
    {
        $vehicles = $this->vehicleRepository->getList();
        return array_map([$this->vehiclesBuilder, 'entityToDto'], $vehicles);
    }
}
