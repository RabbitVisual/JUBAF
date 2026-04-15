@php
    $recaptchaService = app(\App\Services\RecaptchaService::class);
    $isEnabled = $recaptchaService->isEnabled();
    $siteKey = $recaptchaService->getSiteKey();
    $action = $action ?? 'submit';
@endphp

@if($isEnabled && !empty($siteKey))
    <!-- Google reCAPTCHA v3 -->
    @once
    <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}" async defer></script>
    @endonce
    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (!form) return;

            // Store original button content
            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            let originalButtonContent = null;
            if (submitButton) {
                originalButtonContent = submitButton.innerHTML;
            }

            // Execute reCAPTCHA when form is submitted
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button to prevent double submission
                if (submitButton) {
                    submitButton.disabled = true;
                    if (originalButtonContent && submitButton.innerHTML.trim() === originalButtonContent.trim()) {
                        submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processando...';
                    }
                }

                // Check if grecaptcha is loaded
                if (typeof grecaptcha === 'undefined') {
                    console.error('reCAPTCHA script not loaded');
                    alert('Erro ao carregar reCAPTCHA. Por favor, recarregue a página e tente novamente.');
                    if (submitButton && originalButtonContent) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonContent;
                    }
                    return;
                }

                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ $siteKey }}', {
                        action: '{{ $action }}'
                    }).then(function(token) {
                        // Add token to form
                        document.getElementById('g-recaptcha-response').value = token;

                        // Re-enable submit button and restore content
                        if (submitButton && originalButtonContent) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonContent;
                        }

                        // Submit form
                        form.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        alert('Erro ao validar reCAPTCHA. Por favor, recarregue a página e tente novamente.');

                        // Re-enable submit button and restore content
                        if (submitButton && originalButtonContent) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonContent;
                        }
                    });
                });
            });
        });
    </script>
@endif

