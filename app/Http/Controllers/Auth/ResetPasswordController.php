<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ValidatesRecaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    use ValidatesRecaptcha;

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, string $token)
    {
        if (module_enabled('Homepage')) {
            return view('homepage::auth.reset-password', ['token' => $token, 'email' => $request->email]);
        }
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        // Validar reCAPTCHA se estiver habilitado
        $this->validateRecaptcha($request, 'reset_password');

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password)
                ])->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Sua senha foi redefinida com sucesso! Você já pode fazer login com sua nova senha.');
        }

        // Traduzir mensagens de erro comuns
        $errorMessages = [
            Password::INVALID_TOKEN => 'O token de redefinição de senha é inválido ou expirou. Solicite um novo link.',
            Password::INVALID_USER => 'Não encontramos um usuário com este endereço de e-mail.',
            Password::THROTTLED => 'Muitas tentativas. Por favor, aguarde alguns minutos antes de tentar novamente.',
        ];

        $errorMessage = $errorMessages[$status] ?? 'Ocorreu um erro ao redefinir sua senha. Por favor, tente novamente.';

        throw ValidationException::withMessages([
            'email' => [$errorMessage],
        ]);
    }
}

