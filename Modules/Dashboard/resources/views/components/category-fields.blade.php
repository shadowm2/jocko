<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Category Name') }}
    </flux:label>
    <flux:input
        required
        :placeholder="__('dashboard::strings.Enter Category Name')"
        wire:model="form.name"
    />
    <flux:error name="form.name" />
</flux:field>

<flux:field style="z-index: 50; position: relative">
    <flux:label>
        {{ __('dashboard::attributes.Category Parent') }}
    </flux:label>
    <flux:select
        :placeholder="__('dashboard::strings.Select Category Parent')"
        wire:model="form.parent"
    >
        <flux:select.option value="">
            {{ __('dashboard::strings.Category Without Parent') }}
        </flux:select.option>
        @foreach ($categories as $category)
            <flux:select.option :value="$category->slug">
                {{ str_repeat('— ', $category->depth) }}
                {{ $category->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.parent" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Category Type') }}
    </flux:label>
    <flux:select
        required
        :placeholder="__('dashboard::strings.Select Category Type')"
        wire:model="form.type"
    >
        @foreach ($types as $type)
            <flux:select.option :value="$type->value">
                {{ __('dashboard::strings.category_types.' . $type->name) }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.type" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Category Description') }}
    </flux:label>
    <flux:input
        :placeholder="__('dashboard::strings.Enter Category Description')"
        wire:model="form.description"
    />
    <flux:error name="form.description" />
</flux:field>

<x-dashboard::icon-picker
    required
    :placeholder="__('dashboard::strings.Select Category Icon')"
    wire:model="form.icon"
    placeholder="salam"
    :label="__('dashboard::attributes.Category Icon')"
/>

<flux:field>
    <flux:label>
        {{ __('dashboard::attributes.Is Active') }}
    </flux:label>
    <flux:switch wire:model="form.is_active" />
    <flux:error name="form.is_active" />
</flux:field>
