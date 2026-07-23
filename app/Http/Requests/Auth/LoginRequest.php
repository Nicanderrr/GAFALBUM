<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_number' => ['required', 'string'],
            'password' => [$this->routeIs('admin.login.store') ? 'required' : 'nullable', 'string'],
        ];
    }

    /**
     * Attempt to authenticate a regular user's service number.
     *
     * @throws ValidationException
     */
    public function authenticateUser(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('service_number', $this->string('service_number'))->first();

        if (! $user || $user->is_admin) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'service_number' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Attempt to authenticate an admin's service number and password.
     *
     * @throws ValidationException
     */
    public function authenticateAdmin(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('service_number', $this->string('service_number'))->first();

        if (! $user || ! $user->is_admin || ! Hash::check((string) $this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'service_number' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'service_number' => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->string('service_number')).'|'.$this->ip());
    }
}
