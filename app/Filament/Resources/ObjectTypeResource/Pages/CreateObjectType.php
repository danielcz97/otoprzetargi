<?php

namespace App\Filament\Resources\ObjectTypeResource\Pages;

use App\Filament\Resources\ObjectTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateObjectType extends CreateRecord
{
    protected static string $resource = ObjectTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (request()->has('model_type')) {
            $data['model_type'] = request()->query('model_type');
        }
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
