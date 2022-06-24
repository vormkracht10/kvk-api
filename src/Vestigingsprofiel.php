<?php

namespace Vormkracht10\KvKApi;

use Swis\JsonApi\Client\Item;
use Swis\JsonApi\Client\Concerns\HasLinks;

class Vestigingsprofiel extends Item
{
    use HasLinks;

    protected $type = 'vestigingsprofiel';

    public function basisProfiel()
    {
        return $this->hasOne(Basisprofiel::class);
    }
}
