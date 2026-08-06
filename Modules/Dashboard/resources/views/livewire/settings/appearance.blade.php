<section class="w-full">
    <x-dashboard::partials.settings-heading />

    <flux:heading class="sr-only">{{ __('dashboard::strings.Appearance settings') }}</flux:heading>

    <x-dashboard::settings.layout
        :heading="__('dashboard::strings.Appearance')"
        :subheading="__('dashboard::strings.Update the appearance settings for your account')"
    >
        <flux:radio.group
            x-data
            variant="segmented"
            x-model="$flux.appearance"
        >
            <flux:radio
                value="light"
                icon="sun"
            >{{ __('dashboard::strings.Light') }}</flux:radio>
            <flux:radio
                value="dark"
                icon="moon"
            >{{ __('dashboard::strings.Dark') }}</flux:radio>
            <flux:radio
                value="system"
                icon="computer-desktop"
            >{{ __('dashboard::strings.System') }}</flux:radio>
        </flux:radio.group>
    </x-dashboard::settings.layout>
</section>
