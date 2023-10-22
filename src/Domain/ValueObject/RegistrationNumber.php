<?php

namespace Domain\ValueObject;

class RegistrationNumber
{
    private string $number;

    public function __construct(string $number)
    {
        $this->number = strtoupper($number);
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
