<x-filament::layouts.app :title="$this->getTitle()">
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('assets/img/hero-bg.jpg') }}');">
        <div class="w-full max-w-md bg-white/90 backdrop-blur-md p-8 rounded-xl shadow-xl">
            {{ $this->form }}
        </div>
    </div>
</x-filament::layouts.app>

