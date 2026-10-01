@if (!isset($order))
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
@endif
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

<flux:field>
    <flux:label>
        {{ __('order::attributes.Description') }}
    </flux:label>
    <flux:textarea
        wire:model="form.description"
        :placeholder="__('order::strings.Enter order description')"
    />
    <flux:error name="form.mobile" />
</flux:field>
