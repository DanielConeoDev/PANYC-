<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsuarioNotificado extends Notification
{
    use Queueable;

    public $datos;

    /**
     * Crea una nueva instancia de la notificación.
     */
    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    /**
     * Canales de entrega de la notificación.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Representación del mensaje de correo.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mensaje = new MailMessage();

        $mensaje->greeting('Hola ' . $notifiable->name . ',');

        // Mensaje principal del sistema
        $mensaje->line($this->datos['mensaje'] ?? 'Tienes una actualización en tu cuenta de usuario.');

        // Mensaje personalizado desde la base de datos (si existe)
        if (!empty($this->datos['mensaje_usuario'])) {
            $mensaje->line('')
                    ->line('📌 *Tu Clave:*')
                    ->line($this->datos['mensaje_usuario']);
        }

        // Información del usuario
        if (!empty($this->datos['email'])) {
            $mensaje->line('📧 Correo electrónico: ' . $this->datos['email']);
        }

        if (!empty($this->datos['rol'])) {
            $mensaje->line('👤 Rol asignado: ' . $this->datos['rol']);
        }

        if (!empty($this->datos['url'])) {
            $mensaje->action('Ingresar al sistema', $this->datos['url']);
        }

        $mensaje->line('')
                ->line('Si tú no solicitaste este cambio, por favor ignora este mensaje.');

        return $mensaje;
    }

    /**
     * Representación del array (opcional).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mensaje' => $this->datos['mensaje'] ?? null,
            'mensaje_usuario' => $this->datos['mensaje_usuario'] ?? null,
        ];
    }
}
