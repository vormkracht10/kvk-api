<?php

namespace Vormkracht10\KvKApi\Company;

class Address
{
    public function __construct(
        string $type,
        string $street,
        int $houseNumber,
        string $postalCode,
        string $city,
        string $country
    ) {
        $this->type = $type;
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouseNumber(): int
    {
        return $this->houseNumber;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function get(): array
    {
        return [
            'type' => $this->type,
            'street' => $this->street,
            'houseNumber' => $this->houseNumber,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'country' => $this->country,
        ];
    }
}
