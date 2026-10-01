<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('dashboard::strings.Provinces List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('provinces.create')"
                variant="primary"
            >
                {{ __('dashboard::strings.Province Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
