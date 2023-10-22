<?php

namespace App\Controller;

use Domain\DTO\VehicleDTO;
use Persistence\Repository\VehicleRepository;
use Domain\Service\{VehiclesBuilder, VehiclesReader, VehiclesWriter, VehicleValidator};
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class VehicleController extends BaseController
{
    private VehiclesReader $vehiclesReader;
    private VehiclesWriter $vehiclesWriter;

    public function __construct()
    {
        $builder = new VehiclesBuilder();
        $repository = new VehicleRepository();
        $this->vehiclesReader = new VehiclesReader($repository, $builder);
        $this->vehiclesWriter = new VehiclesWriter($repository, $builder, new VehicleValidator());
    }

    public function index(): Response
    {
        ob_start();
        include __DIR__ . '/../views/index.php';
        return (new Response(ob_get_clean()))->send();
    }

    public function list(): JsonResponse
    {
        $results = $this->vehiclesReader->getAllVehicles();
        return $this->toJsonResponse(['results' => $results]);
    }

    public function save(?int $id, Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $dto = $this->createDto($content, $id);
        $response = $this->vehiclesWriter->saveVehicle($dto);

        return $this->toJsonResponse($response, $response['success'] ? 200 : 400);
    }

    public function delete(int $id): JsonResponse
    {
        $this->vehiclesWriter->deleteById($id);
        return $this->toJsonResponse([$id]);
    }

    private function createDto(array $content, ?int $id = null): VehicleDTO
    {
        $dto = new VehicleDTO();
        $dto->id = $id;
        $dto->registrationNumber = $content['registrationNumber'];
        $dto->brand = $content['brand'];
        $dto->model = $content['model'];
        $dto->type = $content['type'];

        return $dto;
    }
}
