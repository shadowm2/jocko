<div>
    <x-table.data-table
        :title="__('order::strings.Orders List')"
        :$rows
        :$columns
        row-component="order::components.order-table-row"
    />
</div>
