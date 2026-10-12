<div
    wire:loading
    role="status"
    aria-live="polite"
    class="absolute inset-0 z-10 rounded bg-white/70 text-sm text-zinc-700 backdrop-blur-[1px] dark:bg-zinc-900/70 dark:text-zinc-200"
>
    <div class="flex h-full w-full items-center justify-center gap-2">
        <flux:icon.loading class="size-6 animate-spin" />
        <span>{{ __('strings.Loading') }}</span>
    </div>
</div>
