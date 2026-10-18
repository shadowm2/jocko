<div class="flex flex-col gap-6">
    <x-auth::auth-header
        :title="__('auth::strings.Log in with SMS code')"
        :description="$codeSent
            ? __('auth::strings.A login code has been sent to :mobile', ['mobile' => $mobile])
            : __('auth::strings.Enter your mobile number to receive a login code')"
    />

    @if (! $codeSent)
        <form
            class="flex flex-col gap-6"
            wire:key="mobile-step"
            wire:submit="sendCode"
        >
            <flux:input
                wire:model="mobile"
                :label="__('auth::attributes.Mobile')"
                type="text"
                inputmode="tel"
                required
                autofocus
                autocomplete="tel"
                placeholder="09121234567"
            />

            <flux:button
                variant="primary"
                type="submit"
                class="w-full"
                data-test="send-code-button"
            >
                {{ __('auth::strings.Send code') }}
            </flux:button>
        </form>
    @else
        <form
            class="flex flex-col gap-6"
            wire:key="code-step"
            wire:submit="login"
        >
            <flux:input
                wire:model="code"
                :label="__('auth::attributes.Code')"
                type="text"
                inputmode="numeric"
                maxlength="{{ Modules\Auth\Services\OtpService::LENGTH }}"
                required
                autofocus
                autocomplete="one-time-code"
                class="text-center tracking-[0.5em]"
                dir="ltr"
            />

            <flux:checkbox
                wire:model="remember"
                :label="__('auth::strings.Remember Me')"
            />

            <flux:button
                variant="primary"
                type="submit"
                class="w-full"
                data-test="otp-login-button"
            >
                {{ __('auth::strings.Login') }}
            </flux:button>
        </form>

        <flux:error name="mobile" />

        <div class="flex justify-between text-sm">
            <flux:link
                as="button"
                type="button"
                wire:click="sendCode"
            >
                {{ __('auth::strings.Resend code') }}
            </flux:link>
            <flux:link
                as="button"
                type="button"
                wire:click="changeNumber"
            >
                {{ __('auth::strings.Change number') }}
            </flux:link>
        </div>
    @endif

    <div class="text-center text-sm">
        <flux:link
            :href="route('login')"
            wire:navigate
        >{{ __('auth::strings.Log in with password') }}</flux:link>
    </div>
</div>
