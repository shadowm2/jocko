@php
    $order ??= null;
@endphp

<form
    class="space-y-8"
    wire:submit.prevent="save"
>
    <flux:heading size="lg">
        {{ __("order::strings.:user User's Order", ['user' => $order?->user->fullName() ?? '']) }}
    </flux:heading>
    <flux:card class="space-y-4">
        <x-order::order-fields
            :$users
            :$userCars
            :order="$order"
        />
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('order::strings.Save Order') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
