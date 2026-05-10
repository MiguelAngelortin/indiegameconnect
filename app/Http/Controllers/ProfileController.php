<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class ProfileController extends Controller
{
    /**
     * Sube una imagen de perfil a Cloudinary y devuelve la URL segura (HTTPS).
     * Método privado reutilizado en update() cuando el usuario cambia su foto.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string  URL segura de la imagen en Cloudinary
     */
    private function uploadToCloudinary($file): string
    {
        // Instancia Cloudinary con las credenciales definidas en .env
        $cloudinary = new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ]
            ])
        );

        // getRealPath() devuelve la ruta temporal del archivo en el servidor
        $uploaded = $cloudinary->uploadApi()->upload($file->getRealPath());

        // Devuelve la URL HTTPS permanente donde quedará alojada la imagen
        return $uploaded['secure_url'];
    }

    /**
     * Muestra el formulario de edición del perfil del usuario logueado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // Pasa el usuario logueado a la vista
        ]);
    }

    /**
     * Valida y guarda los cambios del perfil del usuario logueado.
     * La validación se delega a ProfileUpdateRequest en lugar de hacerse aquí.
     * Si cambia el email, se resetea la verificación. Si sube foto, se envía a Cloudinary.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // fill() rellena el modelo con los campos validados por ProfileUpdateRequest
        $request->user()->fill($request->validated());

        // Si el email ha cambiado, se invalida la verificación anterior
        // isDirty() detecta si el campo fue modificado respecto al valor en BD
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Si se sube nueva foto de perfil, se envía a Cloudinary y se actualiza la URL
        if ($request->hasFile('profile_img')) {
            $request->user()->profile_img = $this->uploadToCloudinary($request->file('profile_img'));
        }

        // save() persiste todos los cambios acumulados en el modelo en la BD
        $request->user()->save();

        // Redirige al perfil público del usuario con un flash de confirmación
        return redirect()->to('/users/' . $request->user()->id)
                         ->with('status', 'profile-updated');
    }

    /**
     * Elimina permanentemente la cuenta del usuario logueado.
     * Requiere confirmación con la contraseña actual antes de proceder.
     * Cierra la sesión, elimina el usuario y destruye la sesión completamente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Valida que la contraseña introducida coincide con la actual del usuario
        // validateWithBag agrupa los errores en 'userDeletion' para mostrarlos en la vista correcta
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Se cierra la sesión antes de eliminar el usuario
        Auth::logout();

        $user->delete();

        // Se invalida la sesión y se regenera el token CSRF para evitar reutilización
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}