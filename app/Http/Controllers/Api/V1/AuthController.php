<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return $this->successResponse(
            'Pengguna berhasil didaftarkan. Silakan verifikasi email Anda.',
            $user,
            201
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $identifier = $request->login ?? $request->email;
        $data = $this->authService->login($identifier, $request->password);

        return $this->successResponse('Login berhasil.', $data);
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        $data = $this->authService->refresh($request->refresh_token);

        return $this->successResponse('Token berhasil diperbarui.', $data);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse('Berhasil logout.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->successResponse('Data pengguna berhasil diambil.', $request->user());
    }

    public function verifyEmail(Request $request, string $id, string $hash): JsonResponse
    {
        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return $this->errorResponse('Tautan verifikasi tidak valid.', 403);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->successResponse('Email sudah diverifikasi.');
        }

        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));

        return $this->successResponse('Email berhasil diverifikasi.');
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->successResponse('Email sudah diverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->successResponse('Tautan verifikasi telah dikirim.');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->successResponse(__($status));
        }

        return $this->errorResponse(__($status));
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset($request->validated(), function ($user, $password) {
            $user->forceFill([
                'password' => \Illuminate\Support\Facades\Hash::make($password)
            ])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return $this->successResponse(__($status));
        }

        return $this->errorResponse(__($status));
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and login/register.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $data = $this->authService->loginOrRegisterWithGoogle($googleUser);

            // Redirect back to frontend (/login) with the access token in a query parameter
            $accessToken = $data['token']['access_token'];
            return redirect('/login?token=' . urlencode($accessToken));
        } catch (\Exception $e) {
            return redirect('/login?error=' . urlencode('Autentikasi Google gagal: ' . $e->getMessage()));
        }
    }
}
