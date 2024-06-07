<?php

namespace App\Providers;

use App\Filament\Resources\MovablePropertyResource;
use App\Filament\Resources\NoticeResource;
use App\Filament\Resources\PropertyResource;
use Illuminate\Support\ServiceProvider;
use App\Models\MovableProperty;
use App\Models\ObjectTypeResource;
use App\Models\TransactionTypeResource;
use Filament\Navigation\NavigationItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Filament\Facades\Filament::serving(function () {
            \Filament\Facades\Filament::registerNavigationItems(self::getNavigationItems());
        });
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Nieruchomości')
                ->url(PropertyResource::getUrl('index'))
                ->icon('heroicon-o-rectangle-stack')
                ->group('Nieruchomości'),
            NavigationItem::make('Typy obiektów')
                ->url(\App\Filament\Resources\ObjectTypeResource::getUrl('index', ['model_type' => 'App\\Models\\Property']))
                ->group('Nieruchomości'),
            NavigationItem::make('Typy transakcji')
                ->url(\App\Filament\Resources\TransactionTypeResource::getUrl('index', ['model_type' => 'App\\Models\\Property']))
                ->group('Nieruchomości'),
            NavigationItem::make('Ruchomości')
                ->url(MovablePropertyResource::getUrl('index'))
                ->icon('heroicon-o-rectangle-stack')
                ->group('Ruchomości'),
            NavigationItem::make('Typy obiektów')
                ->url(\App\Filament\Resources\ObjectTypeResource::getUrl('index', ['model_type' => MovableProperty::class]))
                ->group('Ruchomości'),
            NavigationItem::make('Typy transakcji')
                ->url(\App\Filament\Resources\TransactionTypeResource::getUrl('index', ['model_type' => MovableProperty::class]))
                ->group('Ruchomości'),
            NavigationItem::make('Komunikaty')
                ->url(NoticeResource::getUrl('index'))
                ->icon('heroicon-o-rectangle-stack')
                ->group('Komunikaty'),
            NavigationItem::make('Typy obiektów')
                ->url(\App\Filament\Resources\ObjectTypeResource::getUrl('index', ['model_type' => 'App\\Models\\Comunicats']))
                ->group('Komunikaty'),
            NavigationItem::make('Typy transakcji')
                ->url(\App\Filament\Resources\TransactionTypeResource::getUrl('index', ['model_type' => 'App\\Models\\Comunicats']))
                ->group('Komunikaty'),
        ];
    }
}
