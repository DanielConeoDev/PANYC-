<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // Configuración del LanguageSwitch
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ->locales(['es', 'en']) // puedes agregar más si quieres
            ->labels([
                'es' => 'Español',
                'en' => 'Inglés',
            ])
            ->displayLocale('es') // muestra los nombres en español
            ->visible(outsidePanels: true); // visible fuera del panel también
    });

    // Registro manual del componente Livewire (si es necesario)
    Livewire::component('costos-alimento-list', \App\Http\Livewire\CostosAlimentoList::class);

    
}

}
