<form
    class="space-y-8"
    wire:submit.prevent="save"
>
    <flux:heading size="lg">
        {{ __("order::strings.:user User's Order", ['user' => $order->user->fullName()]) }}
    </flux:heading>
    <flux:card class="space-y-6">
        <flux:field>
            <flux:label>
                {{ __('order::attributes.User Name') }}
            </flux:label>

            <flux:select wire:model.live="form.user_id">

                <flux:select.option value="">
                    {{ __('car::strings.Select Company') }}
                </flux:select.option>

                @foreach ($users as $user)
                    <flux:select.option :value="$user->id">
                        {{ $user->fullName() }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.user_id" />
        </flux:field>
        <flux:field>
            <flux:label>
                {{ __('order::attributes.User Car') }}
            </flux:label>

            <flux:select wire:model.live="form.user_car_id">

                <flux:select.option value="">
                    {{ __('car::strings.Select Company') }}
                </flux:select.option>

                @foreach ($userCars as $userCar)
                    <flux:select.option :value="$userCar->id">
                        {{ $userCar->car->name }}
                    </flux:select.option>
                @endforeach

            </flux:select>

            <flux:error name="form.user_car_id" />
        </flux:field>
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('order::strings.Save Order') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
