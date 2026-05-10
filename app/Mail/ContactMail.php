<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable para el formulario de contacto.
 * Se construye en ContactController@send y se envía al email del administrador
 * definido en MAIL_USERNAME del .env con los datos del remitente.
 */
class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    // Propiedades públicas — al ser públicas son accesibles directamente en la vista Blade
    // sin necesidad de pasarlas explícitamente con compact() o with()
    public string $senderName;
    public string $senderEmail;
    public string $senderMessage;
    public string $senderSubject;

    /**
     * Recibe los datos del formulario de contacto y los asigna a las propiedades
     * públicas para que estén disponibles en la vista del email.
     *
     * @param  string  $name     Nombre del remitente
     * @param  string  $email    Email del remitente
     * @param  string  $message  Mensaje escrito en el formulario
     * @param  string  $subject  Asunto seleccionado en el formulario
     */
    public function __construct(string $name, string $email, string $message, string $subject)
    {
        $this->senderSubject = $subject;
        $this->senderName    = $name;
        $this->senderEmail   = $email;
        $this->senderMessage = $message;
    }

    /**
     * Define el asunto que aparecerá en la bandeja de entrada del administrador.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IndieGameConnect — New Contact Message',
        );
    }

    /**
     * Indica qué vista Blade se usará para renderizar el cuerpo del email.
     * La vista tiene acceso a todas las propiedades públicas de la clase.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
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