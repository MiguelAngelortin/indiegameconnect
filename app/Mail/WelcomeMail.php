<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

/**
 * Mailable para el email de bienvenida.
 * Se envía automáticamente al usuario recién registrado desde
 * RegisteredUserController tras crear la cuenta.
 */
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    // Propiedad pública — el objeto User completo estará disponible
    // directamente en la vista Blade del email como $user
    public User $user;

    /**
     * Recibe el usuario recién registrado y lo asigna a la propiedad pública
     * para que la vista del email pueda acceder a sus datos (nombre, email, etc.)
     *
     * @param  \App\Models\User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Define el asunto que verá el usuario en su bandeja de entrada.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to IndieGameConnect!',
        );
    }

    /**
     * Indica qué vista Blade se usará para renderizar el cuerpo del email.
     * La vista tiene acceso a $user y todos sus atributos directamente.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    /**
     * Define los archivos adjuntos del email.
     * En este caso no se adjunta nada.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}