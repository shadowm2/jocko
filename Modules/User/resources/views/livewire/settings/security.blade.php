<section class="w-full">
    <x-dashboard::partials.settings-heading />

    <flux:heading class="sr-only">{{ __('Security settings') }}</flux:heading>

    <x-dashboard::settings.layout
        :heading="__('auth::strings.Update password')"
        :subheading="__('auth::strings.Ensure your account is using a long, random password to stay secure')"
    >
        <form
            method="POST"
            wire:submit="updatePassword"
            class="mt-6 space-y-6"
        >
            <flux:input
                wire:model="current_password"
                :label="__('auth::attributes.Current password')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />
            <flux:input
                wire:model="password"
                :label="__('auth::attributes.New password')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('auth::attributes.Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center gap-4">
                <flux:button
                    variant="primary"
                    type="submit"
                    data-test="update-password-button"
                >{{ __('Save') }}</flux:button>
            </div>
        </form>

    </x-dashboard::settings.layout>

</section>
