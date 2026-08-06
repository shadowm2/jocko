<section class="w-full">
    <x-dashboard::partials.settings-heading />

    <flux:heading class="sr-only">{{ __('dashboard::strings.Profile settings') }}</flux:heading>

    <x-dashboard::settings.layout
        :heading="__('dashboard::strings.Profile')"
        :subheading="__('dashboard::strings.Update your name and email address')"
    >
        <form
            wire:submit="updateProfileInformation"
            class="my-6 w-full space-y-6"
        >
            <flux:input
                wire:model="first_name"
                :label="__('dashboard::attributes.First Name')"
                type="text"
                required
                autofocus
                autocomplete="first_name"
            />
            <flux:input
                wire:model="last_name"
                :label="__('dashboard::attributes.Last Name')"
                type="text"
                required
                autofocus
                autocomplete="last_name"
            />
            <flux:input
                wire:model="mobile"
                :label="__('dashboard::attributes.Mobile')"
                type="text"
                required
                autofocus
                autocomplete="mobile"
            />

            <div>
                <flux:input
                    wire:model="email"
                    :label="__('dashboard::attributes.Email')"
                    type="email"
                    autocomplete="email"
                />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('dashboard::strings.Your email address is unverified') }}

                            <flux:link
                                class="text-sm cursor-pointer"
                                wire:click.prevent="resendVerificationNotification"
                            >
                                {{ __('dashboard::strings.Click here to re-send the verification email') }}
                            </flux:link>
                        </flux:text>

                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <flux:button
                    variant="primary"
                    type="submit"
                >
                    {{ __('dashboard::attributes.Save') }}
                </flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:user.settings.delete-user-form />
        @endif
    </x-dashboard::settings.layout>
</section>
