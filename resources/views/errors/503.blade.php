@component('layouts.error')
    <flux:heading size="xl">
        {{ __('errors.Service Unavailable') }}
    </flux:heading>

    <flux:text class="text-zinc-500">
        {{ __('errors.The service is temporarily unavailable. Please try again in a few minutes') }}
    </flux:text>

    <div class="flex justify-center gap-3">

        <flux:button
            variant="ghost"
            href="{{ request()->fullUrl() }}"
        >
            {{ __('errors.Refresh') }}
        </flux:button>

        <flux:button href="{{ url('/') }}">
            {{ __('errors.Home') }}
        </flux:button>

    </div>
@endcomponent
