<?php

namespace Modules\Auth\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Modules\Auth\Actions\Fortify\CreateNewUser;
use Modules\Auth\Actions\Fortify\ResetUserPassword;
use Modules\User\Enums\UserType;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();

        Fortify::authenticateUsing(function (Request $request) {
            $request->validate([
                'mobile' => 'required|exists:Modules\User\Models\User,mobile,type,'.UserType::Admin->value,
                'password' => 'required',
            ]);
            $authData = [
                ...$request->only(['mobile', 'password']),
                'type' => UserType::Admin,
            ];

            $logged_in = Auth::attempt($authData);

            return $logged_in ? Auth::user() : null;
        });
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(view: 'auth::login');
        Fortify::registerView('auth::register');
        Fortify::verifyEmailView('livewire.auth.verify-email');
        Fortify::confirmPasswordView('auth::confirm-password');
        Fortify::resetPasswordView('livewire.auth.reset-password');
        Fortify::requestPasswordResetLinkView('livewire.auth.forgot-password');
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

    }
}
