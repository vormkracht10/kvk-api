<?php

namespace Vormkracht10\KvKApi\Models;

use Swis\JsonApi\Client\Concerns\HasLinks;
use Swis\JsonApi\Client\Item;

class Vestigingsprofiel extends Item
{
    use HasLinks;

    protected $type = 'vestigingsprofiel';

    public function basisProfiel()
    {
        return $this->hasOne(Basisprofiel::class);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
