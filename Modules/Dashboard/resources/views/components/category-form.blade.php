<form
    class="space-y-8"
    wire:submit="save"
    xmlns:flux="http://www.w3.org/1999/html"
    style="z-index: 50; position: relative"
>
    <flux:card class="space-y-4">
        <x-dashboard::category-fields
            :$categories
            :$types
        />
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('dashboard::strings.Save Category') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
