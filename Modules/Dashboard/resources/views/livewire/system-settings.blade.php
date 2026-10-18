<form
    class="w-full max-w-2xl space-y-6"
    wire:submit="save"
>
    <div>
        <flux:heading
            size="xl"
            level="1"
        >
            {{ __('dashboard::strings.System Settings') }}
        </flux:heading>
        <flux:subheading>{{ __('dashboard::strings.System Settings Description') }}</flux:subheading>
    </div>

    @foreach ($groups as $group => $definition)
        <flux:card class="space-y-4">
            <flux:heading>{{ __($definition['label']) }}</flux:heading>

            @foreach ($definition['fields'] as $name => $field)
                @php
                    $secret = $field['secret'] ?? false;
                    $isSet = in_array("{$group}.{$name}", $stored, true);
                @endphp

                <flux:field wire:key="setting-{{ $group }}-{{ $name }}">
                    <div class="flex items-center justify-between gap-2">
                        <flux:label>{{ __($field['label']) }}</flux:label>
                        @if ($secret)
                            <div class="flex items-center gap-2">
                                <flux:badge
                                    size="sm"
                                    :color="$isSet ? 'green' : 'zinc'"
                                >
                                    {{ $isSet ? __('dashboard::strings.Set') : __('dashboard::strings.Not Set') }}
                                </flux:badge>
                                @if ($isSet)
                                    <flux:button
                                        type="button"
                                        size="xs"
                                        variant="ghost"
                                        wire:click="clear('{{ $group }}.{{ $name }}')"
                                        wire:confirm="{{ __('dashboard::strings.Clear this setting?') }}"
                                    >
                                        {{ __('dashboard::strings.Clear') }}
                                    </flux:button>
                                @endif
                            </div>
                        @endif
                    </div>

                    <flux:input
                        wire:model="values.{{ $group }}.{{ $name }}"
                        :type="$secret ? 'password' : 'text'"
                        :placeholder="$secret && $isSet ? '••••••••••••' : ''"
                        autocomplete="off"
                        :dir="$field['dir'] ?? 'ltr'"
                    />
                    <flux:error name="values.{{ $group }}.{{ $name }}" />
                </flux:field>
            @endforeach
        </flux:card>
    @endforeach

    <flux:button
        type="submit"
        variant="primary"
    >
        {{ __('dashboard::strings.Save Settings') }}
    </flux:button>
</form>
