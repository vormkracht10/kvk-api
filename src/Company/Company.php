<?php

namespace Vormkracht10\KvKApi\Company;

class Company
{
    private $kvkNumber;

    private $locationNumber;

    private $tradeNames;

    private $registrationDate;

    private $addresses;

    public function __construct(
        int $kvkNumber,
        int $locationNumber,
        array $tradeNames,
        string $registrationDate,
        array $addresses
    ) {
        $this->kvkNumber = $kvkNumber;
        $this->locationNumber = $locationNumber;
        $this->tradeNames = $tradeNames;
        $this->registrationDate = $registrationDate;
        $this->addresses = $addresses;
    }

    public function getKvkNumber(): int
    {
        return $this->kvkNumber;
    }

    public function getLocationNumber(): int
    {
        return $this->locationNumber;
    }

    public function getTradeNames(): array
    {
        return $this->tradeNames;
    }

    public function getRegistrationDate(): string
    {
        return $this->registrationDate;
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function get(): array
    {
        return [
            'kvkNumber' => $this->kvkNumber,
            'locationNumber' => $this->locationNumber,
            'tradeNames' => $this->tradeNames,
            'registrationDate' => $this->registrationDate,
            'addresses' => $this->addresses,
        ];
    }
}
