<!-- resources/views/livewire/contact-form.blade.php -->
<form wire:submit.prevent="enviarFormulario" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
    <div class="row gy-4">

        <div class="col-md-6">
            <input type="text" wire:model.defer="name" class="form-control" placeholder="Tu Nombre">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-6">
            <input type="email" wire:model.defer="email" class="form-control" placeholder="Tu Correo">
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-12">
            <input type="text" wire:model.defer="subject" class="form-control" placeholder="Asunto">
            @error('subject') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-12">
            <textarea wire:model.defer="message" class="form-control" rows="6" placeholder="Mensaje"></textarea>
            @error('message') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-12 text-center">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <button type="submit">Enviar Mensaje</button>
        </div>

    </div>
</form>
