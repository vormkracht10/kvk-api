<?php

namespace Vormkracht10\KvKApi\Company;

class Company
{
    private string $kvkNumber;
    private ?string $establishmentNumber;
    private ?string $tradeName;
    /** @var array<mixed>|null */
    private ?array $addresses;
    /** @var array<string>|null */
    private ?array $websites;

    /**
     * @param array<mixed>|null $addresses
     * @param array<string>|null $websites
     */
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

    public function getEstablishmentNumber(): ?string
    {
        return $this->establishmentNumber;
    }

    public function getTradeName(): ?string
    {
        return $this->tradeName;
    }

    /**
     * @return array<Address>|null
     */
    public function getAddresses(): ?array
    {
        if ($this->addresses === null) {
            return null;
        }

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

    /**
     * @return array<string>|null
     */
    public function getWebsites(): ?array
    {
        return $this->websites;
    }

    /**
     * @return array<string, mixed>
     */
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