<form
    class="space-y-8"
    wire:submit="save"
>
    <flux:field>
        <flux:label>
            {{ __('car::attributes.Color Name') }}
        </flux:label>

        <flux:input
            :placeholder="__('car::strings.Enter color name')"
            wire:model="form.name"
        />

        <flux:error name="form.name" />
    </flux:field>

    <flux:field>
        <flux:label>
            {{ __('car::attributes.Color Name') }}
        </flux:label>

        <flux:input
            type="color"
            :placeholder="__('car::strings.Enter color name')"
            wire:model="form.hex"
        />

        <flux:error name="form.hex" />
    </flux:field>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('car::strings.Save Color') }}
        </flux:button>
        <flux:button
            variant="ghost"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
