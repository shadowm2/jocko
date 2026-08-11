@component('auth::layouts.auth', [
    'title' => __('dashboard::strings.Confirm password'),
])
    <div class="flex flex-col gap-6">
        <x-auth::auth-header
            :title="__('dashboard::strings.Confirm password')"
            :description="__(
                'dashboard::strings.This is a secure area of the application. Please confirm your password before continuing',
            )"
        />

        <x-auth::auth-session-status
            class="text-center"
            :status="session('status')"
        />

        <form
            method="POST"
            action="{{ route('password.confirm.store') }}"
            class="flex flex-col gap-6"
        >
            @csrf

            <flux:input
                name="password"
                :label="__('dashboard::attributes.Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('dashboard::attributes.Password')"
                viewable
            />

            <flux:button
                variant="primary"
                type="submit"
                class="w-full"
                data-test="confirm-password-button"
            >
                {{ __('dashboard::attributes.Confirm') }}
            </flux:button>
        </form>
    </div>
@endcomponent
