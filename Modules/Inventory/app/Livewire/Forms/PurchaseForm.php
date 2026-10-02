<?php

namespace Modules\Inventory\Livewire\Forms;

use App\Helpers\Utils;
use App\Rules\JalaliDate;
use Exception;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Modules\Inventory\Enums\PurchaseStatus;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Services\PurchaseService;
use Modules\Inventory\Services\WarehouseItemService;
use Morilog\Jalali\Jalalian;

class PurchaseForm extends Form
{
    public ?int $id = null;

    public ?Purchase $purchase = null;

    //    public string $warehouse = '';
    public string $warehouse = 'simonis-llc';

    //    public string $supplier = '';
    public string $supplier = 'supplier-logan-anderson';

    //    public string $order_number = '';
    public string $order_number = 'BX-11158';

    //    public string $ordered_at = '';
    public string $ordered_at = '1404/01/01';

    //    public string $ordered_at_gregorian = '';
    public string $ordered_at_gregorian = '2025/03/21';

    //    public string $expected_at = '';
    public string $expected_at = '1404/01/02';

    //    public string $expected_at_gregorian = '';
    public string $expected_at_gregorian = '2025/03/22';

    //    public string $received_at = '';
    public string $received_at = '1404/01/03';

    //    public string $received_at_gregorian = '';
    public string $received_at_gregorian = '2025/03/23';

    public ?string $notes = '';

    /**
     * Warehouse item slugs currently chosen in the picker.
     *
     * @var array<int, string>
     */
    public array $selected = [];

    /**
     * Per line values, keyed by warehouse item slug.
     *
     * @var array<string, array<string, mixed>>
     */
    public array $items = [];

    /**
     * Keep one entry in items for every selected slug, filling in
     * defaults for newly selected ones and dropping deselected ones so
     * the validation rules below always see a complete set.
     */
    protected function syncLineValues(): void
    {
        $lines = [];

        foreach (array_unique($this->selected) as $slug) {
            $line = array_merge([
                'quantity' => 1,
                'unit_price' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
            ], $this->items[$slug] ?? []);

            $line['quantity'] = (float) $line['quantity'];
            $line['unit_price'] = (float) $line['unit_price'];
            $line['discount'] = (float) $line['discount'];
            $line['tax'] = (float) $line['tax'];

            // Total is always derived, never taken from the request.
            $line['total'] = round($line['quantity'] * $line['unit_price'], 2);

            $lines[$slug] = $line;
        }

        $this->items = $lines;
    }

    /**
     * A purchase being created has not been stored yet, so it reports the
     * display-only NEW status rather than the default that create() will
     * actually apply.
     */
    public function currentStatus(): PurchaseStatus
    {
        return $this->purchase?->status ?? PurchaseStatus::NEW;
    }

    public function isUnsaved(): bool
    {
        return $this->purchase === null;
    }

    public function statusLabel(): string
    {
        return $this->currentStatus()->label();
    }

    public function statusColor(): string
    {
        return $this->currentStatus()->color();
    }

    public function getRules(): array
    {
        return [
            'warehouse' => 'required|string|exists:Modules\Inventory\Models\Warehouse,slug',
            'supplier' => 'required|string|exists:Modules\Inventory\Models\Supplier,slug',
            'order_number' => [
                'required',
                'string',
                Rule::unique(Purchase::class, 'order_number')
                    ->ignore($this->purchase?->id),
            ],
            'ordered_at' => ['required', 'string', new JalaliDate],
            'expected_at' => ['required', 'string', new JalaliDate],
            'received_at' => ['required', 'string', new JalaliDate],
            'notes' => 'nullable|string',
            'selected' => ['nullable', 'array'],
            'selected.*' => [
                'string',
                'distinct',
                Rule::exists('warehouse_items', 'slug'),
            ],
            'items' => ['nullable', 'array'],
            'items.*.quantity' => ['required', 'numeric', 'min:0'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.total' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $this->syncLineValues();

        $data = $this->validate();
        // TODO: $data is kinda valid here. only p2e dates before going on
        $purchaseService = resolve(PurchaseService::class);
        $data['ordered_at'] = Jalalian::fromFormat('Y/m/d', Utils::eDigits($data['ordered_at']))->toCarbon();
        $data['expected_at'] = Jalalian::fromFormat('Y/m/d', Utils::eDigits($data['expected_at']))->toCarbon();
        $data['received_at'] = Jalalian::fromFormat('Y/m/d', Utils::eDigits($data['received_at']))->toCarbon();

        unset($data['selected']);

        if ($this->purchase) {
            $purchaseService->update($this->purchase->id, $data);
        } else {
            $purchaseService->create($data);
        }
    }

    /**
     * Display details for the currently selected warehouse items, keyed by
     * slug, so the line editor can label each row.
     *
     * @return array<string, array{name: string, quantity: float}>
     */
    public function selectedCatalog(): array
    {
        $slugs = array_values(array_filter($this->selected));

        if (empty($slugs)) {
            return [];
        }

        return resolve(WarehouseItemService::class)
            ->getWarehouseItems(['whereIn' => ['slug', $slugs]], paginate: false)
            ->mapWithKeys(static fn ($warehouseItem) => [
                $warehouseItem->slug => [
                    'name' => $warehouseItem->item->name,
                    'quantity' => (float) $warehouseItem->quantity,
                ],
            ])
            ->all();
    }

    /**
     * @throws Exception
     */
    public function setPurchase(Purchase $purchase): void
    {
        $this->purchase = $purchase;
        $this->warehouse = $purchase->warehouse->slug;
        $this->supplier = $purchase->supplier->slug;
        $this->order_number = $purchase->order_number;
        $this->ordered_at = Utils::pDigits($purchase->ordered_at_jalali?->format('Y/m/d') ?? '');
        $this->ordered_at_gregorian = $purchase->ordered_at?->format('Y/m/d') ?? '';
        $this->expected_at = Utils::pDigits($purchase->expected_at_jalali?->format('Y/m/d') ?? '');
        $this->expected_at_gregorian = $purchase->expected_at?->format('Y/m/d') ?? '';
        $this->received_at = Utils::pDigits($purchase->received_at_jalali?->format('Y/m/d') ?? '');
        $this->received_at_gregorian = $purchase->received_at?->format('Y/m/d') ?? '';
        $this->notes = $purchase->notes;

        $this->selected = $this->resolveWarehouseItemSlugs($purchase);
        $this->items = $this->resolveLineValues($purchase);
    }

    /**
     * The picker binds warehouse item slugs, while a purchase stores plain
     * item ids, so map the stored lines back to their warehouse item.
     *
     * @return array<int, string>
     */
    protected function resolveWarehouseItemSlugs(Purchase $purchase): array
    {
        $itemIds = $purchase->items()
            ->pluck('item_id')
            ->all();

        if (empty($itemIds)) {
            return [];
        }

        return resolve(WarehouseItemService::class)
            ->getWarehouseItems([
                'warehouse_id' => $purchase->warehouse_id,
                'whereIn' => ['item_id', $itemIds],
            ], paginate: false)
            ->pluck('slug')
            ->all();
    }

    /**
     * Carry the stored quantities and prices back into the form so editing
     * a purchase does not reset them.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function resolveLineValues(Purchase $purchase): array
    {
        $slugs = $this->resolveWarehouseItemSlugs($purchase);

        if (empty($slugs)) {
            return [];
        }

        $warehouseItems = resolve(WarehouseItemService::class)
            ->getWarehouseItems([
                'warehouse_id' => $purchase->warehouse_id,
                'whereIn' => ['item_id', $purchase->items()->pluck('item_id')->all()],
            ], paginate: false)
            ->keyBy('item_id');

        $lines = [];

        foreach ($purchase->items()->get() as $line) {
            $warehouseItem = $warehouseItems->get($line->item_id);

            if (! $warehouseItem || ! in_array($warehouseItem->slug, $slugs, true)) {
                continue;
            }

            $lines[$warehouseItem->slug] = [
                'quantity' => (float) $line->quantity,
                'unit_price' => (float) $line->unit_price,
                'discount' => (float) $line->discount,
                'tax' => (float) $line->tax,
                'total' => (float) $line->total,
            ];
        }

        return $lines;
    }

    public function validationAttributes(): array
    {
        return [
            'warehouse' => __('inventory::attributes.Purchase Warehouse'),
            'supplier' => __('inventory::attributes.Purchase Supplier'),
            'order_number' => __('inventory::attributes.Purchase Order Number'),
            'ordered_at' => __('inventory::attributes.Purchase Ordered At'),
            'expected_at' => __('inventory::attributes.Purchase Expected At'),
            'received_at' => __('inventory::attributes.Purchase Received At'),
            'selected' => __('inventory::attributes.Warehouse Item'),
            'items.*.quantity' => __('inventory::attributes.Purchase Quantity'),
            'items.*.unit_price' => __('inventory::attributes.Purchase Unit Price'),
            'items.*.total' => __('inventory::attributes.Purchase Total'),
        ];
    }
}
