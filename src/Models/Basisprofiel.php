<?php

namespace Vormkracht10\KvKApi\Models;

use Swis\JsonApi\Client\Concerns\HasLinks;
use Swis\JsonApi\Client\Item;

class Basisprofiel extends Item
{
    use HasLinks;

    protected $type = 'basisprofiel';

    public function vestigingsprofiel()
    {
        return $this->hasOne(Vestigingsprofiel::class);
    }

    public function hoofdvestiging()
    {
        return $this->hasOne(Hoofdvestiging::class);
    }

    public function rechtspersoon()
    {
        return $this->hasOne(Rechtspersoon::class);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
