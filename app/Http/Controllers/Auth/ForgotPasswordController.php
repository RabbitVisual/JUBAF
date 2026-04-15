<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ValidatesRecaptcha;
use App\Models\User;
use Modules\Pessoas\App\Models\PessoaCad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    use ValidatesRecaptcha;
    /**
     * Mostrar formulário de recuperação de senha
     */
    public function showForgotPasswordForm()
    {
        if (module_enabled('Homepage')) {
            return view('homepage::auth.forgot-password');
        }
        return view('auth.forgot-password');
    }

    /**
     * Processar solicitação de recuperação de senha
     */
    public function sendResetLink(Request $request)
    {
        // Validar reCAPTCHA se estiver habilitado
        $this->validateRecaptcha($request, 'forgot_password');

        $request->validate([
            'recovery_type' => 'required|in:email,cpf',
            'email' => 'required_if:recovery_type,email|email|nullable',
            'cpf' => 'required_if:recovery_type,cpf|string|nullable',
            'data_nascimento' => 'required_if:recovery_type,cpf|date|nullable',
        ], [
            'recovery_type.required' => 'Selecione o tipo de recuperação.',
            'recovery_type.in' => 'Tipo de recuperação inválido.',
            'email.required_if' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'cpf.required_if' => 'O campo CPF é obrigatório.',
            'data_nascimento.required_if' => 'O campo data de nascimento é obrigatório.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
        ]);

        try {
            if ($request->recovery_type === 'email') {
                // Recuperação por email (método padrão do Laravel)
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                if ($status === Password::RESET_LINK_SENT) {
                    return back()->with('status', trans('passwords.sent'));
                }

                // Traduzir mensagens de erro usando o sistema de tradução do Laravel
                $errorMessage = trans("passwords.{$status}", [], 'pt_BR');
                
                // Se não encontrar tradução, usar mensagens padrão
                if ($errorMessage === "passwords.{$status}") {
                    $errorMessages = [
                        Password::INVALID_USER => trans('passwords.user', [], 'pt_BR'),
                        Password::THROTTLED => trans('passwords.throttled', [], 'pt_BR'),
                    ];
                    $errorMessage = $errorMessages[$status] ?? 'Ocorreu um erro ao enviar o link de redefinição. Por favor, tente novamente.';
                }

                throw ValidationException::withMessages([
                    'email' => [$errorMessage],
                ]);
            } else {
                // Recuperação por CPF + data de nascimento
                $cpf = preg_replace('/[^0-9]/', '', $request->cpf ?? '');

                if (strlen($cpf) !== 11) {
                    throw ValidationException::withMessages([
                        'cpf' => ['CPF deve conter 11 dígitos.'],
                    ]);
                }

                // Buscar usuário pelo CPF
                $user = User::where('cpf', $cpf)->first();

                if (!$user) {
                    throw ValidationException::withMessages([
                        'cpf' => ['CPF não encontrado no sistema.'],
                    ]);
                }

                // Validar data de nascimento
                $dataNascimento = Carbon::parse($request->data_nascimento)->format('Y-m-d');

                // Verificar se há PessoaCad relacionada com este CPF e data de nascimento
                $pessoaCad = PessoaCad::where('num_cpf_pessoa', $cpf)->first();

                if ($pessoaCad) {
                    // Se encontrou PessoaCad, validar data de nascimento
                    $dataNascPessoa = $pessoaCad->data_nascimento;

                    if ($dataNascPessoa) {
                        $dataNascPessoaFormatada = $dataNascPessoa->format('Y-m-d');

                        if ($dataNascPessoaFormatada !== $dataNascimento) {
                            throw ValidationException::withMessages([
                                'data_nascimento' => ['A data de nascimento não confere com os dados cadastrados.'],
                            ]);
                        }
                    } else {
                        // Se não tem data de nascimento na PessoaCad, não pode validar
                        throw ValidationException::withMessages([
                            'data_nascimento' => ['Não foi possível validar a data de nascimento. Entre em contato com o suporte.'],
                        ]);
                    }
                } else {
                    // Se não encontrou PessoaCad, não pode validar por CPF + data
                    throw ValidationException::withMessages([
                        'cpf' => ['Não foi possível validar os dados. Use a recuperação por e-mail ou entre em contato com o suporte.'],
                    ]);
                }

                // Se chegou aqui, validação passou - enviar email de redefinição
                $status = Password::sendResetLink(
                    ['email' => $user->email]
                );

                if ($status === Password::RESET_LINK_SENT) {
                    return back()->with('status', 'Link de redefinição de senha enviado para o e-mail cadastrado: ' . $this->maskEmail($user->email));
                }

                // Traduzir mensagens de erro
                $errorMessages = [
                    Password::INVALID_USER => 'Não encontramos um usuário com este endereço de e-mail.',
                    Password::THROTTLED => 'Por favor, aguarde alguns minutos antes de tentar novamente.',
                ];

                $errorMessage = $errorMessages[$status] ?? 'Ocorreu um erro ao enviar o link de redefinição. Por favor, tente novamente.';

                throw ValidationException::withMessages([
                    'cpf' => [$errorMessage],
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erro ao processar recuperação de senha', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw ValidationException::withMessages([
                'recovery_type' => ['Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.'],
            ]);
        }
    }

    /**
     * Mascarar email para exibição
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $username = $parts[0];
        $domain = $parts[1];

        if (strlen($username) <= 2) {
            $maskedUsername = str_repeat('*', strlen($username));
        } else {
            $maskedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);
        }

        return $maskedUsername . '@' . $domain;
    }
}

