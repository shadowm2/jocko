<?php

namespace Modules\Order\Livewire\Forms;

use App\Helpers\Utils;
use App\Rules\JalaliDate;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Modules\Order\Enums\PaymentMethod;
use Modules\Order\Models\Payment;
use Modules\Order\Services\PaymentService;
use Morilog\Jalali\Jalalian;

class PaymentForm extends Form
{
    public ?Payment $payment = null;

    public string $order = '';

    public string $amount = '';

    public string $method = PaymentMethod::CASH->value;

    public string $paid_at = '';

    public string $paid_at_gregorian = '';

    public ?string $description = null;

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'order' => 'required|string|exists:orders,slug',
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'paid_at' => ['required', 'string', new JalaliDate],
            'description' => 'nullable|string',
        ];
    }

    /**
     * New payments are dated today unless the user picks another day.
     */
    public function setDefaults(): void
    {
        $this->paid_at = Utils::pDigits(Jalalian::now()->format('Y/m/d')) ?? '';
        $this->paid_at_gregorian = now()->format('Y/m/d');
    }

    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
        $this->order = $payment->order->slug;
        $this->amount = Utils::formatQuantity($payment->amount, 2, '');
        $this->method = $payment->method->value;
        $this->paid_at = Utils::pDigits($payment->paid_at_jalali?->format('Y/m/d') ?? '') ?? '';
        $this->paid_at_gregorian = $payment->paid_at?->format('Y/m/d') ?? '';
        $this->description = $payment->description;
    }

    public function save(): Payment
    {
        $this->amount = (string) $this->number($this->amount);

        $data = $this->validate();
        $data['paid_at'] = Jalalian::fromFormat('Y/m/d', Utils::eDigits($data['paid_at']))->toCarbon();

        $service = resolve(PaymentService::class);

        if ($this->payment) {
            // A payment stays with the order it was recorded for.
            unset($data['order']);
            $service->update($this->payment, $data);

            return $this->payment;
        }

        return $service->create($data);
    }

    /**
     * Accept Persian digits and separators as typed.
     */
    protected function number(mixed $value): float
    {
        if (is_string($value)) {
            $value = str_replace(['٫', '٬', ','], ['.', '', ''], Utils::eDigits($value));
        }

        return is_numeric($value) ? (float) $value : 0;
    }

    /** @return array<string, string> */
    public function validationAttributes(): array
    {
        return [
            'order' => __('order::attributes.Order Number'),
            'amount' => __('order::attributes.Amount'),
            'method' => __('order::attributes.Payment Method'),
            'paid_at' => __('order::attributes.Paid At'),
        ];
    }
}
