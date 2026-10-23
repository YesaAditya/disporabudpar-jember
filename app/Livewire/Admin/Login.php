<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin-login')]
#[Title('Masuk Admin · Disporabudpar Jember')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $ingat = false;

    public function masuk()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $kunci = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($kunci, 5)) {
            $detik = RateLimiter::availableIn($kunci);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$detik} detik.",
            ]);
        }

        // Hanya akun dengan users.aktif = 1 yang boleh masuk.
        $berhasil = Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
            'aktif' => true,
        ], $this->ingat);

        if (! $berhasil) {
            RateLimiter::hit($kunci);

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi salah, atau akun tidak aktif.',
            ]);
        }

        RateLimiter::clear($kunci);
        session()->regenerate();

        return $this->redirectIntended(route('admin.dashboard'));
    }

    public function render()
    {
        return view('livewire.admin.login');
    }
}
