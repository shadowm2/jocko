@component('layouts.error')
    <flux:heading size="xl">
        {{ __('errors.Too Many Requests') }}
    </flux:heading>

    <flux:text class="text-zinc-500">
        {{ __('errors.You have made too many requests in a short period. Please wait a moment and try again') }}
    </flux:text>

    <div class="flex justify-center gap-3">

        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('errors.Go Back') }}
        </flux:button>

        <flux:button href="{{ url('/') }}">
            {{ __('errors.Home') }}
        </flux:button>

    </div>
@endcomponent
