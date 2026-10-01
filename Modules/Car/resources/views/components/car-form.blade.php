<form
    class="space-y-8"
    wire:submit="save"
>
    <flux:card class="space-y-4">
        <x-car::car-fields :$companies />
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('car::strings.Save Car') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
