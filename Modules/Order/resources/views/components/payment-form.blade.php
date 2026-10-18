@php
    $payment ??= null;
    $orders ??= collect();
@endphp

<form
    class="space-y-8"
    wire:submit.prevent="save"
>
    <flux:heading size="lg">
        {{ $payment ? __('order::strings.Edit Payment') : __('order::strings.Submit Payment') }}
    </flux:heading>
    <flux:card class="space-y-4">
        <x-order::payment-fields
            :$orders
            :$payment
        />
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('order::strings.Save Payment') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
