@component('auth::layouts.auth', [
    'title' => __('auth::strings.Sign Up'),
])
    <div class="flex flex-col gap-6">
        <x-auth::auth-header
            :title="__('auth::strings.Create account')"
            :description="__('auth::strings.Enter your details below to create your account')"
        />

        <!-- Session Status -->
        <x-auth::auth-session-status
            class="text-center"
            :status="session('status')"
        />

        <form
            method="POST"
            action="{{ route('register.store') }}"
            class="flex flex-col gap-6"
        >
            @csrf
            <!-- First Name -->
            <flux:input
                name="first_name"
                :label="__('auth::attributes.First Name')"
                :value="old('first_name')"
                type="text"
                required
                autofocus
                autocomplete="first_name"
                :placeholder="__('auth::attributes.First Name')"
            />
            <!-- Last Name -->
            <flux:input
                name="last_name"
                :label="__('auth::attributes.Last Name')"
                :value="old('last_name')"
                type="text"
                required
                autofocus
                autocomplete="last_name"
                :placeholder="__('auth::attributes.Last Name')"
            />

            <!-- Email Address -->
            <flux:input
                name="mobile"
                :label="__('auth::attributes.Mobile')"
                :value="old('mobile')"
                type="text"
                required
                autocomplete="mobile"
                placeholder="09121234567"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('auth::attributes.Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('auth::attributes.Password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('auth::attributes.Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('auth::attributes.Confirm Password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button
                    type="submit"
                    variant="primary"
                    class="w-full"
                    data-test="register-user-button"
                >
                    {{ __('auth::strings.Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('auth::strings.Already have an account?') }}</span>
            <flux:link
                :href="route('login')"
                wire:navigate
            >
                {{ __('auth::strings.Login') }}
            </flux:link>
        </div>
    </div>
@endcomponent
