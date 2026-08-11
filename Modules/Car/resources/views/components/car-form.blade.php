<form
    class="space-y-8"
    wire:submit="save"
>
    <flux:field>
        <flux:label>
            {{ __('car::attributes.Car Name') }}
        </flux:label>

        <flux:input
            :placeholder="__('car::strings.Enter Car Name')"
            wire:model.live="form.name"
        />

        <flux:error name="form.name" />
    </flux:field>

    <flux:field>
        <flux:label>
            {{ __('car::attributes.Car Company') }}
        </flux:label>

        <flux:select wire:model="form.car_company">
            <flux:select.option value="">
                {{ __('car::strings.Select Car Company') }}
            </flux:select.option>
            @foreach ($companies as $company)
                <flux:select.option :value="$company->slug">
                    {{ $company->name }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:error name="form.car_company" />
    </flux:field>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('car::strings.Save Car') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
