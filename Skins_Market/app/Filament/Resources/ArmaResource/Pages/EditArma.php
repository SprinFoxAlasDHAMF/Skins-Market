<?php

namespace App\Filament\Resources\ArmaResource\Pages;

use App\Filament\Resources\ArmaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArma extends EditRecord
{
    protected static string $resource = ArmaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
