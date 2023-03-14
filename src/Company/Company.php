<?php

namespace Vormkracht10\KvKApi\Company;

class Company
{
    private $kvkNumber;

    private $establishmentNumber;

    private $tradeName;

    private $addresses;

    private $websites;

    public function __construct(
        string $kvkNumber,
        ?string $establishmentNumber,
        ?string $tradeName,
        ?array $addresses,
        ?array $websites
    ) {
        $this->kvkNumber = $kvkNumber;
        $this->establishmentNumber = $establishmentNumber;
        $this->tradeName = $tradeName;
        $this->addresses = $addresses;
        $this->websites = $websites;
    }

    public function getKvkNumber(): string
    {
        return $this->kvkNumber;
    }

    public function getEstablishmentNumber(): string
    {
        return $this->establishmentNumber;
    }

    public function getTradeName(): ?string
    {
        return $this->tradeName;
    }

    /**
     * @return Address[]
     */
    public function getAddresses(): ?array
    {
        $addresses = [];

        foreach ($this->addresses as $address) {
            $addresses[] = new Address(
                $address->type,
                $address->straatnaam ?? null,
                $address->huisnummer ?? null,
                $address->postcode,
                $address->plaats,
                $address->land
            );
        }

        return $addresses;
    }

    public function getWebsites(): array
    {
        return $this->websites;
    }

    public function get(): array
    {
        return [
            'kvkNumber' => $this->kvkNumber,
            'establishmentNumber' => $this->establishmentNumber,
            'tradeName' => $this->tradeName,
            'addresses' => $this->addresses,
            'websites' => $this->websites,
        ];
    }
}
