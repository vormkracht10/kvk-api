<?php

namespace Vormkracht10\KvKApi\Models;

use Swis\JsonApi\Client\Concerns\HasLinks;
use Swis\JsonApi\Client\Item;

class Hoofdvestiging extends Item
{
    use HasLinks;

    protected $type = 'hoofdvestiging';

    public function basisprofiel()
    {
        return $this->hasOne(Basisprofiel::class);
    }

    public function vestigingsprofiel()
    {
        // TODO: Change this to hasMany
        return $this->hasOne(Vestigingsprofiel::class);
    }
}
