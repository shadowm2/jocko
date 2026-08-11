@props(['row'])

<div class="flex flex-row gap-4 items-center">
    <span>
        {{ mb_strtoupper($row->hex) }}
    </span>

    <span class="rounded-full border-2 border-zinc-600">
        <span
            class="flex size-6 rounded-full"
            style="background-color: {{ $row->hex }}; border: 2px solid {{ \App\Helpers\Utils::darken($row->hex) }}"
        ></span>
    </span>
</div>
