<?php

namespace Modules\Auth\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Auth\Services\OtpService;
use Modules\Dashboard\Exceptions\SmsException;

class OtpLogin extends Component
{
    public string $mobile = '';

    public string $code = '';

    public bool $remember = false;

    public bool $codeSent = false;

    public function sendCode(OtpService $otp): void
    {
        $this->validate(['mobile' => 'required|string|max:20']);

        try {
            $otp->send($this->mobile);
        } catch (SmsException $e) {
            // The provider's message can reveal account state (credit, key), so only debug shows it.
            $this->addError('mobile', config('app.debug') ? $e->getMessage() : __('auth::strings.Could not send the code. Please try again later.'));

            return;
        }

        $this->reset('code');
        $this->codeSent = true;
    }

    public function login(OtpService $otp): void
    {
        $this->validate(['code' => 'required|string|max:12']);

        $user = $otp->verify($this->mobile, $this->code);

        if (! $user) {
            $this->reset('code');
            $this->addError('code', __('auth::strings.The code is invalid or has expired.'));

            return;
        }

        Auth::login($user, $this->remember);
        session()->regenerate();

        $this->redirectIntended(config('fortify.home'));
    }

    public function changeNumber(): void
    {
        $this->reset('code', 'codeSent');
        $this->resetErrorBag();
    }

    public function render(): View
    {
        return view('auth::livewire.otp-login')
            ->layout('auth::layouts.auth', ['title' => __('auth::strings.Log in with SMS code')]);
    }
}
