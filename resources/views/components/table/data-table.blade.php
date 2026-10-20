@props([
    'columns' => [],
    'rows' => [],
    'title' => null,
    'rowComponent',
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

    <div class="relative overflow-hidden">
        <x-table.loading />

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
                    @component($rowComponent, [
                        'row' => $row,
                        ...$rowProps,
                    ])
                    @endcomponent
                @endforeach
            </flux:table.rows>

        </flux:table>
        @if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $rows->links() }}
        @endif
    </div>

</div>
