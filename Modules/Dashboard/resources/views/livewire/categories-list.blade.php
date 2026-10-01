<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('dashboard::strings.Categories List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('categories.create')"
                variant="primary"
            >
                {{ __('dashboard::strings.Category Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
