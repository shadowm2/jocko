<form
    wire:submit="save"
    class="space-y-8"
>

    <flux:card class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ __('user::strings.User Car') }}
            </flux:heading>

            <flux:text class="mt-2">
                {{ __('user::strings.Enter the details of the vehicle') }}
            </flux:text>
        </div>

        <x-user::user-car-fields
            :$companies
            :$cars
        />
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('user::strings.Save Car') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>

</form>
