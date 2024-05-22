<?php

namespace App\Filament\Resources\MovablePropertyResource\Pages;

use App\Filament\Resources\MovablePropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovableProperty extends EditRecord
{
    protected static string $resource = MovablePropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
