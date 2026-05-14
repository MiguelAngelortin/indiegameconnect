<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    /**
     * Muestra el formulario de contacto.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('contact');
    }

    /**
     * Valida los datos del formulario de contacto y envía un email
     * al administrador de la plataforma con el mensaje del usuario.
     * Redirige de vuelta al formulario con un mensaje de confirmación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // Validación de los campos del formulario antes de procesar nada
        $validated = $request->validate([
            'subject' => 'required|string',
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|max:2000',
        ]);

        // Envía el email al buzón del administrador definido en .env (MAIL_TO)
        // ContactMail construye el Mailable con los datos del remitente
        Mail::to(env('MAIL_TO'))->send(
            new ContactMail($validated['name'], $validated['email'], $validated['message'], $validated['subject'])
        );

        return redirect('/contact')->with('success', 'Your message has been sent. We will get back to you soon!');
    }
}