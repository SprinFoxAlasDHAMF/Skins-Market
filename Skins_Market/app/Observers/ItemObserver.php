<?php

namespace App\Observers;

use App\Models\Item;
use App\Services\ItemTypeFactory;

class ItemObserver
{
    public function created(Item $item): void
    {
        ItemTypeFactory::handle($item);
    }

    public function updated(Item $item): void
    {
        if ($item->isDirty('tipo')) {
            ItemTypeFactory::migrateType($item);
        } else {
            ItemTypeFactory::sync($item);
        }
    }

    public function deleted(Item $item): void
    {
        ItemTypeFactory::delete($item);
    }
}
