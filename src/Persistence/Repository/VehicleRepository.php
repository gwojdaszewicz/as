<?php

namespace Persistence\Repository;

use App\SQLiteConnection;
use Domain\Entity\Vehicle;
use Domain\Repository\VehicleRepositoryInterface;
use Domain\ValueObject\RegistrationNumber;
use Domain\ValueObject\VehicleType;

class VehicleRepository implements VehicleRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new SQLiteConnection())->connect();
    }

    public function getList(): array
    {
        $results = $this->pdo->query('SELECT * FROM vehicles');

        $items = [];
        foreach ($results as $row) {
            $items[] = $this->rowToEntity($row);
        }

        return $items;
    }

    public function getById(int $id): ?Vehicle
    {
        $stmt = $this->pdo->prepare('SELECT * FROM vehicles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->rowToEntity($row);
    }

    public function deleteById($id): void
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM vehicles WHERE id = :id');
            $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error deleting record ($id): " . $e->getMessage());
        }
    }

    public function persist(Vehicle $vehicle): Vehicle
    {
        try {
            return $vehicle->getId() ? $this->update($vehicle) : $this->create($vehicle);
        } catch (\PDOException $e) {
            throw new \Exception("Error persisting record: " . $e->getMessage());
        }
    }

    private function create(Vehicle $vehicle): Vehicle
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO vehicles (
                    registration_number, 
                    brand, 
                    model, 
                    type, 
                    created_at, 
                    updated_at
                  ) VALUES (
                    :registration_number, 
                    :brand, 
                    :model, 
                    :type, 
                    :created_at, 
                    :updated_at
                  )'
        );
        $stmt->execute([
            'registration_number' => (string)$vehicle->getRegistrationNumber(),
            'brand' => $vehicle->getBrand(),
            'model' => $vehicle->getModel(),
            'type' => $vehicle->getType()->value,
            'created_at' => $vehicle->getCreatedAt()->getTimestamp(),
            'updated_at' => $vehicle->getUpdatedAt()->getTimestamp(),
        ]);

        $vehicle->setId($this->pdo->lastInsertId());

        return $vehicle;
    }

    private function update(Vehicle $vehicle): Vehicle
    {
        $stmt = $this->pdo->prepare(
            'UPDATE vehicles SET 
                        registration_number = :registration_number, 
                        brand = :brand, 
                        model = :model, 
                        type = :type, 
                        created_at = :created_at, 
                        updated_at = :updated_at 
                    WHERE id = :id'
        );
        $stmt->execute([
            'registration_number' => (string)$vehicle->getRegistrationNumber(),
            'brand' => $vehicle->getBrand(),
            'model' => $vehicle->getModel(),
            'type' => $vehicle->getType()->value,
            'created_at' => $vehicle->getCreatedAt()->getTimestamp(),
            'updated_at' => $vehicle->getUpdatedAt()->getTimestamp(),
            'id' => $vehicle->getId(),
        ]);

        return $vehicle;
    }

    private function rowToEntity(array $row): Vehicle
    {
        return new Vehicle(
            new RegistrationNumber($row['registration_number']),
            $row['brand'],
            $row['model'],
            VehicleType::from($row['type']),
            (new \DateTime())->setTimestamp($row['created_at']),
            (new \DateTime())->setTimestamp($row['updated_at']),
            $row['id']
        );
    }
}
