<x-dashboard::layouts.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-dashboard::layouts.sidebar>
