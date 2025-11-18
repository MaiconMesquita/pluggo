<?php

namespace App\Domain\Entity;

use Closure;

class PaginatedEntities
{
    private ?Closure $mapFN = null;

    private string $dataName = 'data';

    public function __construct(
        private int $totalItems,
        private array $items
    ) {}

    public function setMapJSONFn(Closure $fn)
    {
        $this->mapFN = $fn;
    }

    public function toJSON(): array
    {
        $items = $this->items;

        if ($this->mapFN) {
            $items = array_map(
                $this->mapFN,
                $this->items
            );
        } elseif ($this->items && method_exists($this->items[0], 'toJSON')) {
            $items = array_map(
                function ($item) {
                    return $item->toJSON();
                },
                $this->items
            );
        }

        return [
            'totalItems' => $this->totalItems,
            $this->dataName => $items,
        ];
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getTotalItems()
    {
        return $this->totalItems;
    }
}
