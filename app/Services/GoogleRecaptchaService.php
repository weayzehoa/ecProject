<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Env;
use Illuminate\Http\Request;

class GoogleRecaptchaService
{
    public function verifyRequest(Request $request, ?string $version = null, ?string $action = null, float $minScore = 0.5): bool|string
    {
        $token = $request->input('g-recaptcha-response');

        $version = $version ?: ($request->has('recaptcha_version') ? $request->input('recaptcha_version') : 'v2');
        $action = $action ?: $request->input('recaptcha_action', 'login');

        $secret = match ($version) {
            'v2' => env('GOOGLE_NOCAPTCHA_V2_SECRET'),
            'v3' => env('GOOGLE_NOCAPTCHA_V3_SECRET'),
            default => env('GOOGLE_NOCAPTCHA_SECRET'),
        };

        if (empty($token)) {
            return __('recaptcha.required');
        }

        $response = Http::asForm()->post(env('GOOGLE_RECAPTCHA_URL', 'https://www.google.com/recaptcha/api/siteverify'), [
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        if (! $response->ok()) {
            return __('recaptcha.unreachable');
        }

        $data = $response->json();

        if (! ($data['success'] ?? false)) {
            return __('recaptcha.failed');
        }

        if ($version === 'v3') {
            if (($data['action'] ?? '') !== $action || ($data['score'] ?? 0) < $minScore) {
                return __('recaptcha.low_score');
            }
        }

        return true;
    }

    public function v3Input(string $action = 'login'): string
    {
        return <<<HTML
<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
<input type="hidden" name="recaptcha_version" value="v3">
<input type="hidden" name="recaptcha_action" value="{$action}">
HTML;
    }

    public function v3Script(string $action = 'login', bool $showBadge = false): string
    {
        $sitekey = trim(env('GOOGLE_NOCAPTCHA_V3_SITEKEY'));

        if (empty($sitekey)) {
            return '<!-- reCAPTCHA sitekey is not set -->';
        }

        $style = ! $showBadge ? '<style>.grecaptcha-badge { visibility: hidden !important; }</style>' : '';

        return <<<HTML
{$style}
<script src="https://www.google.com/recaptcha/api.js?render={$sitekey}" async defer></script>
<script>
    function executeRecaptchaWhenReady() {
        if (typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
            grecaptcha.ready(function () {
                grecaptcha.execute('{$sitekey}', {action: '{$action}'}).then(function (token) {
                    const el = document.getElementById('g-recaptcha-response');
                    if (el) el.value = token;
                });
            });
        } else {
            setTimeout(executeRecaptchaWhenReady, 100);
        }
    }
    document.addEventListener('DOMContentLoaded', executeRecaptchaWhenReady);
</script>
HTML;
    }

    public function v3(string $action = 'login', bool $showBadge = false): string
    {
        return $this->v3Input($action) . $this->v3Script($action, $showBadge);
    }

    public function v2Script(): string
    {
        return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    }

    public function v2Widget(): string
    {
        $sitekey = env('GOOGLE_NOCAPTCHA_V2_SITEKEY');

        if (empty($sitekey)) {
            return '<!-- reCAPTCHA v2 sitekey is missing -->';
        }

        return "<div class=\"g-recaptcha\" data-sitekey=\"{$sitekey}\"></div>";
    }

    public function script(?string $sitekey = null, string $action = 'login'): string
    {
        return $this->v3Script($action); // fallback for compatibility
    }
}
