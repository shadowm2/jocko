<?php

namespace Modules\Inventory\Livewire\Forms;

use App\Helpers\Utils;
use App\Rules\JalaliDate;
use Exception;
use Illuminate\Validation\Rule;
use Livewire\Form;
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
    public string $supplier = 'ward-bednar';

    //    public string $order_number = '';
    public string $order_number = 'BX-158';

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
     * @var array<int, string>
     */
    public array $items = [];

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
            'items' => ['nullable', 'array'],
            'items.*' => [
                'string',
                'distinct',
                Rule::exists('warehouse_items', 'slug'),
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        try {

            $data = $this->validate();
        } catch (Exception $exception) {
            dd($exception);
        }
        // TODO: $data is kinda valid here. only p2e dates before going on
        $purchaseService = resolve(PurchaseService::class);
        $data['ordered_at'] = Jalalian::fromFormat('Y/m/d', Utils::eDigits($data['ordered_at']))->toCarbon();
        $data['expected_at'] = Jalalian::fromFormat('Y/m/d', Utils::eDigits($data['expected_at']))->toCarbon();
        $data['received_at'] = Jalalian::fromFormat('Y/m/d', Utils::eDigits($data['received_at']))->toCarbon();

        if ($this->purchase) {
            $purchaseService->update($this->purchase->id, $data);
        } else {
            $purchaseService->create($data);
        }
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
        $this->items = $this->resolveWarehouseItemSlugs($purchase);
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

    public function validationAttributes(): array
    {
        return [
            'warehouse' => __('inventory::attributes.Purchase Warehouse'),
            'supplier' => __('inventory::attributes.Purchase Supplier'),
            'order_number' => __('inventory::attributes.Purchase Order Number'),
            'ordered_at' => __('inventory::attributes.Purchase Ordered At'),
            'expected_at' => __('inventory::attributes.Purchase Expected At'),
            'received_at' => __('inventory::attributes.Purchase Received At'),
            'items' => __('inventory::attributes.Warehouse Item'),
        ];
    }
}
