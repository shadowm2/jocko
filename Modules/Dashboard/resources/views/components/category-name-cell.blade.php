<div
    class="flex flex-row items-center gap-2"
    style="padding-inline-start: {{ $row->depth * 1.15 }}rem"
>
    <flux:badge size="sm">
        {{ \App\Helpers\Utils::pDigits($row->depth + 1) }}
    </flux:badge>
    <flux:icon
        name="folder"
        class="size-{{ max(4, 6 - $row->depth) }}"
    />
    {!! $row->name !!}
</div>
