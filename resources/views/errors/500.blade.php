@component('layouts.error')
    <flux:heading size="xl">
        {{ __('errors.Something Went Wrong') }}
    </flux:heading>

    <flux:text class="text-zinc-500">
        {{ __('errors.An unexpected error occurred. Please try again later') }}
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
