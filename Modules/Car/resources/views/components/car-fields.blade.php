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

<x-dashboard::file-upload
    :label="__('car::attributes.Car Images')"
    accept="image/*"
    multiple
/>
