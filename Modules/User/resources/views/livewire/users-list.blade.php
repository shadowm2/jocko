<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('user::strings.Users List')"
        row-component="user::components.user-list-row"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('users.create')"
                variant="primary"
            >
                {{ __('user::strings.Add User') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
