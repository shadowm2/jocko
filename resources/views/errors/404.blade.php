@component('layouts.error', [
    'code' => '404',
    'title' => __('errors.Page Not Found'),
])
    <flux:heading size="xl">
        {{ __('errors.Page Not Found') }}
    </flux:heading>

    <flux:text class="text-zinc-500">
        {{ __("errors.The page you're looking for doesn't exist or may have been moved") }}
    </flux:text>

    <div class="flex justify-center gap-3">

        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('errors.Go Back') }}
        </flux:button>

        <flux:button href="{{ route('home') }}">
            {{ __('errors.Home') }}
        </flux:button>

    </div>
@endcomponent
