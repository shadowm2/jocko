@props([
    'columns' => [],
    'rows' => [],
    'title' => null,
    'rowComponent' => null,
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
                        ])
                        @endcomponent
                    @else
                        <x-table.row>
                            @foreach ($columns as $col)
                                <x-table.cell>
                                    @if (isset($col['component']))
                                        <x-dynamic-component
                                            :component="$col['component']"
                                            :$row
                                        />
                                    @else
                                        {!! isset($col['key'])
                                            ? implode(' ', array_map(fn($key) => data_get($row, $key), explode(',', $col['key'])))
                                            : $col['render']($row) !!}
                                    @endif
                                </x-table.cell>
                            @endforeach
                        </x-table.row>
                    @endif
                @endforeach
            </flux:table.rows>

        </flux:table>
        {{ $rows->links() }}
    </div>

</div>
