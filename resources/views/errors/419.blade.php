@component('layouts.error')
    <flux:heading size="xl">
        {{ __('errors.Page Expired') }}
    </flux:heading>

    <flux:text class="text-zinc-500">
        {{ __('errors.Your session expired. Refresh the page and try again') }}
    </flux:text>

    <div class="flex justify-center gap-3">

        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('errors.Go Back') }}
        </flux:button>

        <flux:button href="{{ request()->fullUrl() }}">
            {{ __('errors.Refresh') }}
        </flux:button>

    </div>
@endcomponent
