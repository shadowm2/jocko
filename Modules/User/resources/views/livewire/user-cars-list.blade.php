<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('user::strings.User :name Cars', [
            'name' => $this->user->first_name . ' ' . $this->user->last_name,
        ])"
    >

    </x-table.data-table>
</div>
