<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * The reCAPTCHA site key.
     */
    private string $siteKey;

    /**
     * The reCAPTCHA secret key.
     */
    private string $secretKey;

    /**
     * Whether reCAPTCHA is enabled.
     */
    private bool $enabled;

    /**
     * The minimum score required for reCAPTCHA v3.
     */
    private float $minScore;

    /**
     * The reCAPTCHA verification URL.
     */
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Create a new RecaptchaService instance.
     */
    public function __construct()
    {
        $this->siteKey = config('services.recaptcha.site_key');
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->enabled = config('services.recaptcha.enabled', true);
        $this->minScore = config('services.recaptcha.min_score', 0.5);
    }

    /**
     * Check if reCAPTCHA is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->enabled && 
               !empty($this->siteKey) && 
               !empty($this->secretKey) &&
               $this->siteKey !== 'your_site_key_here' &&
               $this->secretKey !== 'your_secret_key_here';
    }

    /**
     * Get the reCAPTCHA site key.
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    /**
     * Get the minimum score required.
     */
    public function getMinScore(): float
    {
        return $this->minScore;
    }

    /**
     * Verify the reCAPTCHA token.
     *
     * @param string $token The reCAPTCHA token
     * @param string $action The action name (login, register, etc.)
     * @param string|null $remoteIp The user's IP address
     * @return array The verification result
     */
    public function verify(string $token, string $action, ?string $remoteIp = null): array
    {
        // If reCAPTCHA is disabled, return success
        if (!$this->isEnabled()) {
            return [
                'success' => true,
                'score' => 1.0,
                'action' => $action,
                'challenge_ts' => now()->toISOString(),
                'hostname' => request()->getHost(),
                'disabled' => true
            ];
        }

        try {
            $response = Http::asForm()->post(self::VERIFY_URL, [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $remoteIp ?: request()->ip(),
            ]);

            $result = $response->json();

            // Log the verification attempt
            Log::info('reCAPTCHA verification attempt', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'expected_action' => $action,
                'hostname' => $result['hostname'] ?? null,
                'challenge_ts' => $result['challenge_ts'] ?? null,
                'error_codes' => $result['error-codes'] ?? [],
                'ip_address' => $remoteIp ?: request()->ip(),
            ]);

            // Check if the verification was successful
            if (!($result['success'] ?? false)) {
                return [
                    'success' => false,
                    'error' => 'reCAPTCHA verification failed',
                    'error_codes' => $result['error-codes'] ?? [],
                    'score' => 0.0
                ];
            }

            // Check if the action matches
            if (isset($result['action']) && $result['action'] !== $action) {
                return [
                    'success' => false,
                    'error' => 'Action mismatch',
                    'expected_action' => $action,
                    'received_action' => $result['action'],
                    'score' => $result['score'] ?? 0.0
                ];
            }

            // Check if the score meets the minimum requirement
            $score = $result['score'] ?? 0.0;
            if ($score < $this->minScore) {
                return [
                    'success' => false,
                    'error' => 'Score too low',
                    'score' => $score,
                    'min_score' => $this->minScore
                ];
            }

            return [
                'success' => true,
                'score' => $score,
                'action' => $result['action'] ?? $action,
                'challenge_ts' => $result['challenge_ts'] ?? null,
                'hostname' => $result['hostname'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error', [
                'error' => $e->getMessage(),
                'token' => substr($token, 0, 20) . '...',
                'action' => $action,
                'ip_address' => $remoteIp ?: request()->ip(),
            ]);

            return [
                'success' => false,
                'error' => 'Verification service unavailable',
                'exception' => $e->getMessage(),
                'score' => 0.0
            ];
        }
    }

    /**
     * Validate reCAPTCHA for a specific action.
     *
     * @param string $token The reCAPTCHA token
     * @param string $action The action name
     * @param string|null $remoteIp The user's IP address
     * @return bool Whether the validation passed
     */
    public function validate(string $token, string $action, ?string $remoteIp = null): bool
    {
        $result = $this->verify($token, $action, $remoteIp);
        return $result['success'] ?? false;
    }

    /**
     * Get the reCAPTCHA JavaScript URL.
     */
    public function getJavaScriptUrl(): string
    {
        return "https://www.google.com/recaptcha/api.js?render={$this->siteKey}";
    }

    /**
     * Generate reCAPTCHA HTML for forms.
     *
     * @param string $action The action name
     * @param string $callback The JavaScript callback function
     * @return string The HTML string
     */
    public function getHtml(string $action, string $callback = 'onRecaptchaCallback'): string
    {
        if (!$this->isEnabled()) {
            return '<!-- reCAPTCHA disabled -->';
        }

        return sprintf(
            '<script>
                function executeRecaptcha_%s() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute("%s", {action: "%s"}).then(function(token) {
                            %s(token, "%s");
                        });
                    });
                }
            </script>',
            $action,
            $this->siteKey,
            $action,
            $callback,
            $action
        );
    }
}
