<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Determine if login is email or username
        $loginType = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $this->input('login'),
            'password' => $this->input('password'),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        // Check if user is blocked
        $user = Auth::user();
        if ($user->status_blokir) {
            // Check if block duration has expired
            if ($user->durasi_blokir && Carbon::now()->greaterThan(Carbon::parse($user->durasi_blokir))) {
                // Auto-unblock if duration has passed
                $user->status_blokir = false;
                $user->durasi_blokir = null;
                $user->save();
            } else {
                // User is still blocked
                Auth::logout();

                $durasiBlokir = $user->durasi_blokir ? Carbon::parse($user->durasi_blokir) : null;
                $sisaHari = $durasiBlokir ? (int) ceil(Carbon::now()->diffInDays($durasiBlokir, false)) : 0;

                $message = 'Akun Anda sedang diblokir.';
                if ($durasiBlokir && $sisaHari > 0) {
                    $message .= " Blokir akan berakhir pada " . $durasiBlokir->format('d M Y H:i') . " (sekitar {$sisaHari} hari lagi).";
                } elseif ($durasiBlokir) {
                    $message .= " Blokir akan berakhir pada " . $durasiBlokir->format('d M Y H:i') . ".";
                } else {
                    $message .= " Silakan hubungi admin untuk informasi lebih lanjut.";
                }

                throw ValidationException::withMessages([
                    'login' => $message,
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')) . '|' . $this->ip());
    }
}
