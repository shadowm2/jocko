@props(['row' => null])
<div>
    @if ($row->isLowStock())
        <flux:badge
            color="red"
            icon="exclamation-triangle"
            size="sm"
        >{{ __('inventory::strings.Low Stock') }}</flux:badge>
    @endif
</div>
