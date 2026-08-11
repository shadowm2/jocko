@props([
    'companies' => [],
    'cars' => [],
    'onSubmit' => null,
])
<form
    wire:submit="onSubmit"
    class="space-y-8"
>

    <flux:card class="space-y-6">

        <div>
            <flux:heading size="lg">
                {{ __('user::strings.User Car') }}
            </flux:heading>

            <flux:text class="mt-2">
                {{ __('user::strings.Enter the details of the vehicle') }}
            </flux:text>
        </div>

        <flux:field>
            <flux:label>
                {{ __('car::strings.Car Company') }}
            </flux:label>

            <flux:select wire:model.live="form.car_company_id">

                <flux:select.option value="">
                    {{ __('car::strings.Select Company') }}
                </flux:select.option>

                @foreach ($companies as $company)
                    <flux:select.option :value="$company->id">
                        {{ $company->name }}
                    </flux:select.option>
                @endforeach

            </flux:select>

            <flux:error name="form.car_company_id" />
        </flux:field>

        <flux:field>
            <flux:label>
                {{ __('car::strings.Car Name') }}
            </flux:label>

            <flux:select wire:model="form.car_id">

                <flux:select.option value="">
                    {{ __('car::strings.Select Car') }}
                </flux:select.option>

                @foreach ($cars as $car)
                    <flux:select.option :value="$car->id">
                        {{ $car->name }}
                    </flux:select.option>
                @endforeach

            </flux:select>

            <flux:error name="form.car_id" />
        </flux:field>

        <flux:field>
            <flux:label>
                {{ __('user::attributes.Manufactured At') }}
            </flux:label>

            <flux:input
                class="persian-datepicker"
                wire:model.live="form.manufactured_at"
            />

            <flux:error name="form.manufactured_at" />
        </flux:field>

        <flux:field>
            <flux:label>
                {{ __('user::attributes.User Car Description') }}
            </flux:label>

            <flux:textarea
                placeholder="{{ __('user::strings.Enter User Car Description') }}"
                wire:model="form.description"
                rows="4"
            />

            <flux:error name="form.description" />
        </flux:field>

    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('user::strings.Save Car') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>

</form>
