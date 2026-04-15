<?php

namespace App\Traits;

use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait ValidatesRecaptcha
{
    /**
     * Validate reCAPTCHA token from request
     *
     * @param Request $request
     * @param string $action
     * @return void
     * @throws ValidationException
     */
    protected function validateRecaptcha(Request $request, string $action = 'submit'): void
    {
        $recaptchaService = app(RecaptchaService::class);

        // If reCAPTCHA is disabled, skip validation
        if (!$recaptchaService->isEnabled()) {
            return;
        }

        $token = $request->input('g-recaptcha-response');

        if (empty($token)) {
            throw ValidationException::withMessages([
                'recaptcha' => ['A validação do reCAPTCHA é obrigatória. Por favor, recarregue a página e tente novamente.'],
            ]);
        }

        $minScore = $recaptchaService->getMinScore();

        if (!$recaptchaService->verify($token, $action, $minScore)) {
            throw ValidationException::withMessages([
                'recaptcha' => ['A validação do reCAPTCHA falhou. Por favor, tente novamente.'],
            ]);
        }
    }
}

