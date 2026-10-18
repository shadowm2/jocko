@php
    use App\Helpers\Utils;
@endphp

@if ($payment)
    <flux:field>
        <flux:label>
            {{ __('order::attributes.Order Number') }}
        </flux:label>
        <flux:input
            :value="$payment->order->order_number"
            disabled
        />
    </flux:field>
@else
    <flux:field>
        <flux:label>
            {{ __('order::attributes.Order Number') }}
        </flux:label>

        <flux:select wire:model="form.order">
            <flux:select.option value="">
                {{ __('order::strings.Select Order') }}
            </flux:select.option>

            @foreach ($orders as $order)
                <flux:select.option :value="$order->slug">
                    {{ $order->order_number }} — {{ $order->user->fullName() }}
                    ({{ __('order::attributes.Balance') }}: {{ Utils::formatQuantity((float) $order->items_sum_total - (float) $order->payments_sum_amount, 2, ',') }})
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:error name="form.order" />
    </flux:field>
@endif

<x-dashboard::numeric-input
    wire:model="form.amount"
    error="form.amount"
    :label="__('order::attributes.Amount')"
    :placeholder="__('order::attributes.Amount')"
/>

<flux:field>
    <flux:label>
        {{ __('order::attributes.Payment Method') }}
    </flux:label>

    <flux:select wire:model="form.method">
        @foreach (\Modules\Order\Enums\PaymentMethod::cases() as $method)
            <flux:select.option :value="$method->value">
                {{ $method->label() }}
            </flux:select.option>
        @endforeach
    </flux:select>

    <flux:error name="form.method" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('order::attributes.Paid At') }}
    </flux:label>
    <input
        class="persian-datepicker
                    appearance-none [:where(&)]:w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-[1.375rem]
                    rounded-lg shadow-xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]
                    text-zinc-700 dark:text-zinc-300 disabled:text-zinc-500 dark:disabled:text-zinc-400
                    has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400
                    dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border
                    border-zinc-200 border-b-zinc-300/80 dark:border-white/10"
        wire:model="form.paid_at"
        value="{{ $this->form->paid_at_gregorian }}"
    />
    <flux:error name="form.paid_at" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('order::attributes.Description') }}
    </flux:label>
    <flux:textarea
        wire:model="form.description"
        :placeholder="__('order::strings.Enter payment description')"
    />
    <flux:error name="form.description" />
</flux:field>
