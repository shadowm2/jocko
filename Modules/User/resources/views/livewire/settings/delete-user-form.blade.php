<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('dashboard::strings.Delete account') }}</flux:heading>
        <flux:subheading>{{ __('dashboard::strings.Delete your account and all of its resources') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button
            variant="danger"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >
            {{ __('dashboard::attributes.Delete Account') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal
        name="confirm-user-deletion"
        :show="$errors->isNotEmpty()"
        focusable
        class="max-w-lg"
    >
        <form
            method="POST"
            wire:submit="deleteUser"
            class="space-y-6"
        >
            <div>
                <flux:heading size="lg">
                    {{ __('dashboard::strings.Are you sure you want to delete your account?') }}
                </flux:heading>

                <flux:subheading>
                    {{ __('dashboard::strings.Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account') }}
                </flux:subheading>
            </div>

            <flux:input
                wire:model="password"
                :label="__('dashboard::attributes.Password')"
                type="password"
                viewable
            />

            <div class="flex ltr:justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('dashboard::attributes.Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    type="submit"
                >{{ __('dashboard::attributes.Delete Account') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
