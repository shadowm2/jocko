@props([
    'columns' => [],
    'rows' => [],
    'title' => null,
    'rowComponent' => null,
    'rowProps' => [],
])
<div class="space-y-4">
    <div class="flex flex-row justify-between items-center">
        @if ($title)
            <flux:heading class="text-xl font-bold">
                {{ $title }}
            </flux:heading>
        @endif

        @if (isset($action))
            {{ $action }}
        @endif
    </div>

    <x-table.toolbar>

        {{ $toolbar ?? '' }}

    </x-table.toolbar>

    <div class="overflow-hidden">

        <flux:table>

            <flux:table.columns>

                @foreach ($columns as $col)
                    <x-table.header>
                        {{ $col['label'] ?? '' }}
                    </x-table.header>
                @endforeach

            </flux:table.columns>

            <flux:table.rows>
                @foreach ($rows as $row)
                    @if (!is_null($rowComponent))
                        @component($rowComponent, [
                            'row' => $row,
                            ...$rowProps,
                        ])
                        @endcomponent
                    @else
                        <x-table.row>
                            @foreach ($columns as $col)
                                @php
                                    $val = explode(',', $col['key'] ?? '')
                                                    |> (fn($x) => array_map(fn($key) => data_get($row, $key), $x))
                                                    |> (fn($x) => implode(' ', $x,));
                                    $dynamicAttributes = new \Illuminate\View\ComponentAttributeBag();
                                    if (!empty($col['attrs'])) {
                                        $attrs = $col['attrs']($row);
                                        $dynamicAttributes->setAttributes($attrs);
                                    }
                                @endphp
                                <x-table.cell>
                                    @if (isset($col['component']))
                                        <x-dynamic-component
                                            :component="$col['component']"
                                            :$row
                                            :attributes="$dynamicAttributes"
                                        />
                                    @elseif (($col['type'] ?? false) === 'switch')
                                        @if (isset($col['action']))
                                            <flux:switch
                                                :checked="data_get($row, $col['key'])"
                                                wire:change="{{ $col['action'] }}('{{ data_get($row, $row->getRouteKeyName()) }}')"
                                            />
                                        @else
                                            <flux:switch
                                                :checked="data_get($row, $col['key'])"
                                                disabled
                                            />
                                        @endif
                                    @elseif (($col['type'] ?? false) === 'float')
                                        {{ \App\Helpers\Utils::pDigits(\App\Helpers\Utils::formatQuantity($val, $col['decimals'] ?? 3)) }}
                                    @elseif(isset($col['key']))
                                        {{
                                            \App\Helpers\Utils::pDigits(
                                                !empty($col['maxlength']) && mb_strlen($val) > $col['maxlength']
                                                    ? mb_substr($val, 0, $col['maxlength']) . '...'
                                                    : $val,
                                            )
                                        }}
                                    @else
                                        {!! $col['render']($row) !!}
                                    @endif
                                </x-table.cell>
                            @endforeach
                        </x-table.row>
                    @endif
                @endforeach
            </flux:table.rows>

        </flux:table>
        @if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $rows->links() }}
        @endif
    </div>

</div>
