<?php

namespace App\Filament\Resources\ObjectTypeResource\Pages;

use App\Filament\Resources\ObjectTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObjectType extends EditRecord
{
    protected static string $resource = ObjectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['model_type'] = request()->query('model_type');
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $url = parent::getRedirectUrl();

        if (request()->has('model_type')) {
            $url .= '?model_type=' . request()->query('model_type');
        }

        return $url;
    }
}
