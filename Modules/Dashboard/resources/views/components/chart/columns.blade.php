{{--
    Column chart with one or two series, drawn in plain HTML so it follows the page direction and theme.
    $series is [name => [value per label]]; colours are categorical slots 1 and 2, in that order.
--}}
@props(['labels', 'series', 'labelEvery' => 1])

@php
    use App\Helpers\Utils;

    $colors = ['bg-[#2a78d6] dark:bg-[#3987e5]', 'bg-[#eb6834] dark:bg-[#d95926]'];
    $peak = max(1, ...collect($series)->flatten()->all());
    $magnitude = 10 ** floor(log10($peak));
    // Round the top of the scale up to 1, 2 or 5 times a power of ten.
    $top = collect([1, 2, 5, 10])->map(fn ($step) => $step * $magnitude)->first(fn ($candidate) => $candidate >= $peak);
    $money = fn ($value) => Utils::formatQuantity($value, 2, ',');
@endphp

<div {{ $attributes->class('space-y-3') }}>
    @if (count($series) > 1)
        <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-zinc-600 dark:text-zinc-300">
            @foreach ($series as $name => $values)
                <span class="flex items-center gap-1.5">
                    <span class="size-2.5 rounded-sm {{ $colors[$loop->index] }}"></span>{{ $name }}
                </span>
            @endforeach
        </div>
    @endif

    <div class="flex gap-2 text-xs text-zinc-500">
        <div
            class="relative h-44 w-20 shrink-0"
            aria-hidden="true"
        >
            @foreach ([1, .5, 0] as $fraction)
                <span
                    class="absolute end-0 -translate-y-1/2 tabular-nums"
                    style="top: {{ (1 - $fraction) * 100 }}%"
                >{{ $money($top * $fraction) }}</span>
            @endforeach
        </div>

        <div class="relative h-44 flex-1">
            @foreach ([0, 50, 100] as $offset)
                <div
                    class="absolute inset-x-0 border-t border-zinc-200 dark:border-white/10"
                    style="top: {{ $offset }}%"
                ></div>
            @endforeach

            <div class="relative flex h-full items-end">
                @foreach ($labels as $index => $label)
                    <div
                        class="group relative flex h-full flex-1 items-end justify-center gap-0.5 rounded-md px-px outline-none hover:bg-zinc-100 focus-visible:bg-zinc-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
                        tabindex="0"
                        role="img"
                        aria-label="{{ $label }}: @foreach ($series as $name => $values){{ $name }} {{ $money($values[$index]) }}{{ $loop->last ? '' : '، ' }}@endforeach"
                    >
                        @foreach ($series as $values)
                            <div
                                class="w-full max-w-5 rounded-t-sm {{ $colors[$loop->index] }}"
                                style="height: {{ $values[$index] / $top * 100 }}%"
                            ></div>
                        @endforeach

                        <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-1 hidden min-w-36 -translate-x-1/2 space-y-1 rounded-lg border border-zinc-200 bg-white p-2 text-xs shadow-md group-hover:block group-focus-visible:block dark:border-white/10 dark:bg-zinc-800">
                            <div class="text-zinc-500">{{ Utils::pDigits($label) }}</div>
                            @foreach ($series as $name => $values)
                                <div class="flex items-center gap-2">
                                    <span class="h-0.5 w-3 shrink-0 rounded-full {{ $colors[$loop->index] }}"></span>
                                    <span class="font-semibold tabular-nums text-zinc-900 dark:text-white">{{ $money($values[$index]) }}</span>
                                    <span class="text-zinc-500">{{ $name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex gap-2 text-xs text-zinc-500">
        <div class="w-20 shrink-0"></div>
        <div class="flex flex-1">
            @foreach ($labels as $index => $label)
                <span class="flex-1 text-center whitespace-nowrap">{{ $index % $labelEvery === 0 ? Utils::pDigits($label) : '' }}</span>
            @endforeach
        </div>
    </div>

    <details class="text-sm">
        <summary class="cursor-pointer text-zinc-500">{{ __('dashboard::strings.Show as table') }}</summary>
        <table class="mt-2 w-full">
            <thead>
                <tr class="text-start text-zinc-500">
                    <th class="py-1 text-start font-normal"></th>
                    @foreach ($series as $name => $values)
                        <th class="py-1 text-start font-normal">{{ $name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $index => $label)
                    <tr class="border-t border-zinc-200 dark:border-white/10">
                        <td class="py-1">{{ Utils::pDigits($label) }}</td>
                        @foreach ($series as $values)
                            <td class="py-1 tabular-nums">{{ $money($values[$index]) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </details>
</div>
