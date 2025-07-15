<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MensajeContacto;

class ContactForm extends Component
{
    public $name, $email, $subject, $message;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required|min:3',
        'message' => 'required|min:5',
    ];

    public function enviarFormulario()
    {
        $this->validate();

        MensajeContacto::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset();

        session()->flash('success', '¡Tu mensaje ha sido enviado con éxito!');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
