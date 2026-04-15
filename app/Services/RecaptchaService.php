<?php

namespace App\Services;

use App\Models\SystemConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * Verify reCAPTCHA v3 token
     *
     * @param string $token
     * @param string $action
     * @param float $minScore Minimum score threshold (0.0 to 1.0)
     * @return bool
     */
    public function verify(string $token, string $action = 'submit', float $minScore = 0.5): bool
    {
        // Check if reCAPTCHA is enabled
        if (!$this->isEnabled()) {
            return true; // If disabled, always return true
        }

        $secretKey = $this->getSecretKey();

        if (empty($secretKey)) {
            Log::warning('reCAPTCHA secret key is not configured');
            return false;
        }

        if (empty($token)) {
            Log::warning('reCAPTCHA token is missing');
            return false;
        }

        try {
            // Try Enterprise API first, then fallback to standard API
            $endpoint = 'https://www.google.com/recaptcha/api/siteverify';
            
            $response = Http::timeout(10)->asForm()->post($endpoint, [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->successful()) {
                Log::warning('reCAPTCHA API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            $result = $response->json();

            if (!isset($result['success']) || !$result['success']) {
                Log::warning('reCAPTCHA verification failed', [
                    'errors' => $result['error-codes'] ?? [],
                    'ip' => request()->ip(),
                ]);
                return false;
            }

            // Verify action matches
            if (isset($result['action']) && $result['action'] !== $action) {
                Log::warning('reCAPTCHA action mismatch', [
                    'expected' => $action,
                    'received' => $result['action'],
                ]);
                return false;
            }

            // Verify score meets minimum threshold
            $score = $result['score'] ?? 0;
            if ($score < $minScore) {
                Log::warning('reCAPTCHA score too low', [
                    'score' => $score,
                    'min_score' => $minScore,
                    'ip' => request()->ip(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error', [
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
            // In case of error, allow the request to proceed (fail open)
            // You can change this to return false for stricter security
            return false;
        }
    }

    /**
     * Check if reCAPTCHA is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return SystemConfig::get('recaptcha.enabled', false);
    }

    /**
     * Get reCAPTCHA site key
     *
     * @return string
     */
    public function getSiteKey(): string
    {
        return SystemConfig::get('recaptcha.site_key', '');
    }

    /**
     * Get reCAPTCHA secret key
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return SystemConfig::get('recaptcha.secret_key', '');
    }

    /**
     * Get minimum score threshold
     *
     * @return float
     */
    public function getMinScore(): float
    {
        return (float) SystemConfig::get('recaptcha.min_score', 0.5);
    }
}

