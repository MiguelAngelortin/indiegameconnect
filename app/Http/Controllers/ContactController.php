<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|max:2000',
        ]);

        Mail::to(env('MAIL_USERNAME'))->send(
            new ContactMail($validated['name'], $validated['email'], $validated['message'], $validated['subject'])
        );

        return redirect('/contact')->with('success', 'Your message has been sent. We will get back to you soon!');
    }
}