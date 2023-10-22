<?php

namespace Domain\ValueObject;

enum VehicleType: string
{
    case TRUCK = 'Ciezarowy';
    case BUS = 'Bus';
    case PERSONAL = 'Osobowy';

    public function getDescription(): string
    {
        return match($this) {
            self::TRUCK => 'Ciężarowy',
            self::BUS => 'Bus',
            self::PERSONAL => 'Osobowy',
        };
    }
}

