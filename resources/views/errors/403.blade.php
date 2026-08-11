@component('layouts.error', [
    'code' => 403,
])
    <flux:heading size="xl">
        {{ __('errors.Access Denied') }}
    </flux:heading>

    <flux:text class="text-zinc-500">
        {{ __("errors.You don't have permission to access this page") }}
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
