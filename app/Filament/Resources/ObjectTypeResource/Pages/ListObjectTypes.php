<?php

namespace App\Filament\Resources\ObjectTypeResource\Pages;

use App\Filament\Resources\ObjectTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListObjectTypes extends ListRecords
{
    protected static string $resource = ObjectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn() => static::getResource()::getUrl('create', ['model_type' => request()->query('model_type')])),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()
            ->where('model_type', request()->query('model_type'));
    }
}
