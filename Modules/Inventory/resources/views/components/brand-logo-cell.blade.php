@props(['row' => null])
<div
    class="group inline-flex size-16 shrink-0 overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 p-1 shadow-sm transition-all duration-200 hover:scale-105 hover:shadow-md dark:border-white/10 dark:bg-zinc-800">
    @if ($row->logo instanceof \Modules\Dashboard\Models\Media)
        <img
            class="size-full rounded-lg object-cover"
            alt="{{ $row->logo->original_name }}"
            src="{{ $row->logo->publicPath() }}"
        />
    @else
        <div
            class="flex items-center justify-center bg-black m-auto rounded-xl aspect-square w-[70%] text-white text-lg">
            {{ mb_substr($row->name, 0, 1) }}
        </div>
    @endif
</div>
