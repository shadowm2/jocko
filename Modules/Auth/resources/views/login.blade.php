@component('auth::layouts.auth', [
    'title' => __('auth::strings.Log in to your account'),
])
    <div class="flex flex-col gap-6">
        <x-auth::auth-header
            :title="__('auth::strings.Log in to your account')"
            :description="__('auth::strings.Enter your mobile and password below to log in')"
        />

        <!-- Session Status -->
        <x-auth::auth-session-status
            class="text-center"
            :status="session('status')"
        />

        <form
            method="POST"
            action="{{ route('login.store') }}"
            class="flex flex-col gap-6"
        >
            @csrf
            <!-- Email Address -->
            <flux:input
                name="mobile"
                :label="__('auth::attributes.Mobile')"
                :value="old('mobile')"
                type="text"
                required
                autofocus
                autocomplete="mobile"
                placeholder="09121234567"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('auth::attributes.Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('auth::attributes.Password')"
                    viewable
                />

                {{--                @if (Route::has('password.request')) --}}
                {{--                    <flux:link --}}
                {{--                        class="absolute top-0 text-sm end-0" --}}
                {{--                        :href="route('password.request')" --}}
                {{--                        wire:navigate --}}
                {{--                    > --}}
                {{--                        {{ __('Forgot your password?') }} --}}
                {{--                    </flux:link> --}}
                {{--                @endif --}}
            </div>

            <!-- Remember Me -->
            <flux:checkbox
                name="remember"
                :label="__('auth::strings.Remember Me')"
                :checked="old('remember')"
            />

            <div class="flex items-center justify-end">
                <flux:button
                    variant="primary"
                    type="submit"
                    class="w-full"
                    data-test="login-button"
                >
                    {{ __('auth::strings.Login') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('auth::strings.Don\'t have an account?') }}</span>
            <flux:link
                :href="route('register')"
                wire:navigate
            >{{ __('auth::strings.Sign Up') }}</flux:link>
        </div>
    </div>
@endcomponent
