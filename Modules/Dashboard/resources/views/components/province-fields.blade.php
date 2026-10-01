<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Province Name') }}
    </flux:label>
    <flux:input
        :placeholder="__('dashboard::strings.Enter Province Name')"
        wire:model="form.name"
    />
    <flux:error name="form.name" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Province Country') }}
    </flux:label>
    <flux:select
        :placeholder="__('dashboard::strings.Select Province Country')"
        wire:model="form.country"
    >
        <flux:select.option value="">
            {{ __('dashboard::strings.Select Province Country') }}
        </flux:select.option>
        @foreach ($countries as $country)
            <flux:select.option :value="$country->code">
                {{ __('dashboard::countries.' . $country->code) }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.country" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Is Active') }}
    </flux:label>
    <flux:switch wire:model="form.is_active" />
    <flux:error name="form.is_active" />
</flux:field>
