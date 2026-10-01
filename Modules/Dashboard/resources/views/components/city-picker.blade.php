<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Province Country') }}
    </flux:label>
    <flux:select
        :placeholder="__('dashboard::strings.Select Province Country')"
        wire:model.live="form.country"
    >
        @foreach ($countries as $country)
            <flux:select.option :value="$country->code">
                {{ \Illuminate\Support\Facades\Lang::has('dashboard::countries.' . $country->code) ? __('dashboard::countries.' . $country->code) : $country->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.country" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.City Province') }}
    </flux:label>
    <flux:select
        :placeholder="__('dashboard::strings.Select City Province')"
        wire:model.live="form.province"
    >
        @foreach ($provinces as $province)
            <flux:select.option :value="$province->slug">
                {{ $province->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.province" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.City') }}
    </flux:label>
    <flux:select
        :placeholder="__('dashboard::strings.Select City')"
        wire:model="form.city"
    >
        @foreach ($cities as $city)
            <flux:select.option :value="$city->slug">
                {{ $city->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.city" />
</flux:field>
