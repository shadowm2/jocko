@php use Livewire\Features\SupportFileUploads\TemporaryUploadedFile; @endphp
@props([
    'label' => 'Upload Files',
    'accept' => null,
    'multiple' => false,
])

@php
    $model = $attributes->has('wire:model') ? $attributes->wire('model')->value() : 'form.images';
    $prev = $attributes->has('wire:prev') ? $attributes->wire('prev')->value() : 'form.prev_images';
    /** @var array<int, TemporaryUploadedFile> $newFiles */
    $newFiles = data_get($this, $model);
    $prevFiles = data_get($this, $prev);
    if (!$multiple) {
        if (count($newFiles) > 1) {
            data_set($this, $model, [$newFiles[1]]);
            $newFiles = [$newFiles[1]];
        } elseif (count($prevFiles) === 1 && count($newFiles) === 1) {
            data_set($this, $prev, []);
            $prevFiles = [];
        }
    }
@endphp

<flux:field>

    @if ($label)
        <flux:label>
            {{ $label }}
        </flux:label>
    @endif

    <div
        x-data="{ dragging: false }"
        class="relative"
    >
        <label
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false"
            class="flex min-h-40 cursor-pointer flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed p-6 transition-all duration-200"
            :class="dragging
                ?
                'border-accent bg-accent/5' :
                'border-zinc-300 hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:border-zinc-600 dark:hover:bg-zinc-800/50'"
        >
            <div class="flex size-14 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                <flux:icon.cloud-arrow-up class="size-7 text-zinc-500" />
            </div>

            <div class="space-y-1 text-center">
                <flux:text class="font-medium">
                    {{ __('inventory::strings.Drag and drop files here') }}
                </flux:text>

                <flux:text class="text-sm text-zinc-500">
                    {{ __('inventory::strings.or click to browse') }}
                </flux:text>
            </div>

            <input
                type="file"
                wire:model="{{ $model }}"
                @if ($accept) accept="{{ $accept }}" @endif
                @if ($multiple) multiple @endif
                class="absolute inset-0 cursor-pointer opacity-0"
            >
        </label>
    </div>

    {{-- Selected / existing files --}}
    @if (count($prevFiles))
        <div class="mt-4 grid gap-3">
            @foreach ($prevFiles as $currentFile)
                @php
                    $name = $currentFile->original_name;
                    $size = $currentFile->size;
                    $mimeType = $currentFile->mime_type;
                    $url = $currentFile->publicPath();

                    $isImage = str_starts_with($mimeType ?? '', 'image/');
                @endphp

                <div
                    class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white p-3 shadow-sm transition hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">

                    @if ($isImage && $url)
                        <div
                            class="size-16 shrink-0 overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 p-1 dark:border-zinc-700 dark:bg-zinc-800">
                            <img
                                src="{{ $url }}"
                                alt="{{ $name }}"
                                class="size-full rounded-lg object-cover"
                            >
                        </div>
                    @else
                        <div
                            class="flex size-16 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-800">
                            <flux:icon.document class="size-7 text-zinc-500" />
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <div
                            class="truncate text-sm font-medium"
                            title="{{ $name }}"
                        >
                            {{ $name }}
                        </div>

                        @if ($size)
                            <div class="mt-1 text-xs text-zinc-500">
                                {{ number_format($size / 1024, 1) }} KB
                            </div>
                        @endif
                    </div>

                    <flux:button
                        icon="trash"
                        variant="ghost"
                        style="color: var(--color-red-500); opacity: 90%"
                        wire:click="removeFile('{{ $prev }}', {{ $loop->index }}, @js(!!$multiple))"
                        wire:confirm="{{ __('dashboard::strings.Are you sure you want to remove this file?') }}"
                    />
                </div>
            @endforeach
        </div>
    @endif

    @if (count($newFiles))
        <div class="mt-4 grid gap-3">
            @foreach ($newFiles as $currentFile)
                @php
                    $name = $currentFile->getClientOriginalName();
                    $size = $currentFile->getSize();
                    $mimeType = $currentFile->getMimeType();
                    $url = $currentFile->temporaryUrl();
                    $isImage = str_starts_with($mimeType ?? '', 'image/');
                @endphp

                <div
                    class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white p-3 shadow-sm transition hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">
                    @if ($isImage && $url)
                        <div
                            class="size-16 shrink-0 overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 p-1 dark:border-zinc-700 dark:bg-zinc-800">
                            <img
                                src="{{ $url }}"
                                alt="{{ $name }}"
                                class="size-full rounded-lg object-cover"
                            >
                        </div>
                    @else
                        <div
                            class="flex size-16 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-800">
                            <flux:icon.document class="size-7 text-zinc-500" />
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <div
                            class="truncate text-sm font-medium"
                            title="{{ $name }}"
                        >
                            {{ $name }}
                        </div>

                        @if ($size)
                            <div class="mt-1 text-xs text-zinc-500">
                                {{ number_format($size / 1024, 1) }} KB
                            </div>
                        @endif
                    </div>

                    <flux:button
                        icon="trash"
                        variant="ghost"
                        style="color: var(--color-red-500); opacity: 90%"
                        wire:click="removeFile('{{ $model }}', {{ $loop->index }}, @js(!!$multiple))"
                        wire:confirm="{{ __('dashboard::strings.Are you sure you want to remove this file?') }}"
                    />
                    <span class="rounded-full bg-green-500/10 px-2 py-1 text-xs font-medium text-green-600">
                        {{ __('dashboard::strings.New') }}
                    </span>
                </div>
                @error('form.images.*')
                    <flux:error name="form.images.{{ $loop->index }}">
                        {{ $message }}
                    </flux:error>
                @enderror
            @endforeach
        </div>
    @endif
</flux:field>
