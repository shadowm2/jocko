<form
    class="space-y-8"
    wire:submit="save"
    xmlns:flux="http://www.w3.org/1999/html"
>
    <flux:card class="space-y-4">
        <x-dashboard::city-fields
            :$countries
            :$provinces
        />
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('dashboard::strings.Save City') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
