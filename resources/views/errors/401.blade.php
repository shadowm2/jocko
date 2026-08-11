@component('layouts.error')
    <flux:heading size="xl">
        {{ __('errors.Unauthorized') }}
    </flux:heading>

    <flux:text class="text-zinc-500">
        {{ __('errors.You need to sign in to access this page') }}
    </flux:text>

    <div class="flex justify-center gap-3">

        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('errors.Go Back') }}
        </flux:button>

        <flux:button href="{{ route('login') }}">
            {{ __('errors.Sign In') }}
        </flux:button>

    </div>
@endcomponent
