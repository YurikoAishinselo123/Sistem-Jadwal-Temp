<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\ClientRepository;

class AuthService
{
    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Auto-verify email immediately (no email verification required)
        $user->markEmailAsVerified();

        return $user;
    }

    /**
     * Authenticate user and issue a Personal Access Token.
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi yang Anda masukkan salah.'],
            ]);
        }

        // Revoke existing tokens to avoid accumulation
        $user->tokens()->delete();

        $this->ensurePersonalAccessClientExists();

        // Create a new Personal Access Token
        $tokenResult = $user->createToken('Login');
        $accessToken = $tokenResult->accessToken;

        return [
            'user'  => $user,
            'token' => [
                'token_type'    => 'Bearer',
                'access_token'  => $accessToken,
                'refresh_token' => null,
                'expires_in'    => null,
            ],
        ];
    }

    /**
     * Refresh — not supported for Personal Access Tokens.
     */
    public function refresh(string $refreshToken): array
    {
        throw ValidationException::withMessages([
            'refresh_token' => ['Pembaruan token tidak didukung. Silakan login kembali.'],
        ]);
    }

    /**
     * Revoke the current token for the given user (logout).
     */
    public function logout(User $user): void
    {
        $user->token()->revoke();
    }

    /**
     * Authenticate or register a Google OAuth user and issue a Personal Access Token.
     */
    public function loginOrRegisterWithGoogle(\Laravel\Socialite\Contracts\User $googleUser): array
    {
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        } else {
            $user = User::create([
                'name'      => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
            ]);
            $user->markEmailAsVerified();
        }

        $this->ensurePersonalAccessClientExists();

        $tokenResult = $user->createToken('Google Sign-In');
        $accessToken = $tokenResult->accessToken;

        return [
            'user'  => $user,
            'token' => [
                'token_type'    => 'Bearer',
                'access_token'  => $accessToken,
                'refresh_token' => null,
                'expires_in'    => null,
            ],
        ];
    }

    /**
     * Ensure Passport has a personal access client for the configured provider.
     */
    protected function ensurePersonalAccessClientExists(): void
    {
        $provider = config('auth.guards.api.provider');
        $clientRepository = app(ClientRepository::class);

        try {
            $clientRepository->personalAccessClient($provider);
        } catch (\RuntimeException $exception) {
            $clientRepository->createPersonalAccessGrantClient(
                sprintf('%s Personal Access Client', config('app.name', 'Laravel')),
                $provider
            );
        }
    }
}
