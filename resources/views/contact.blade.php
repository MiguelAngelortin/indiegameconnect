{{-- Extiende el layout maestro que incluye navbar, footer y assets globales --}}
@extends('layouts.app')

@section('title', 'Contact — IndieGameConnect')

@section('content')
    <!-- Contenedor centrado con ancho limitado para el formulario -->
    <div class="container d-flex justify-content-center mt-5">
        <div class="games-form col-md-6">

            <h2 class="form-title mb-4">Contact Support</h2>

            <!-- Texto introductorio con opacidad reducida para jerarquía visual -->
            <p class="text-center mb-4" style="opacity: 0.7; font-size: 1.05rem;">
                Have a <strong>question</strong>, found a <strong>bug</strong> or want to <strong>question</strong> something? <br><br>
                We're here to help. Fill in the form and we'll get back to you as soon as possible.
            </p>

            {{-- Mensaje de éxito tras enviar el formulario correctamente --}}
            {{-- Se muestra con session('success') que ContactController@send establece con with() --}}
            @if(session('success'))
                <div class="alert-box alert-box-success mb-4">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Errores de validación del formulario --}}
            {{-- $errors es una variable global que Laravel inyecta automáticamente en todas las vistas --}}
            @if($errors->any())
                <div class="alert-box alert-box-error mb-4">
                    <p class="mb-2">Please fix the following errors:</p>
                    <ul class="mb-0">
                        {{-- $errors->all() devuelve todos los mensajes de error como array --}}
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulario de contacto — POST a la ruta contact.send --}}
            <form method="POST" action="{{ route('contact.send') }}">
                {{-- @csrf genera un campo hidden con el token de seguridad --}}
                {{-- Laravel lo verifica en cada POST para prevenir ataques CSRF --}}
                @csrf

                <!-- Selector de asunto — define la categoría del mensaje -->
                <div class="mb-3">
                    <label class="form-label">Subject:</label>
                    <select name="subject" id="subject" class="form-select">
                        <option value="support">Technical Support</option>
                        <option value="report">Report Content</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- old('name') repopula el campo con el valor anterior si la validación falla -->
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <!-- textarea usa old() dentro de las etiquetas en lugar de value="" -->
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="5">{{ old('message') }}</textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-register">Send message</button>
                </div>
            </form>

        </div>
    </div>
@endsection