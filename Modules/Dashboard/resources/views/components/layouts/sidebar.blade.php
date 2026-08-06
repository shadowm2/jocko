<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="dark"
    dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}"
>

<head>
    @include('dashboard::partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar
        sticky
        collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
    >
        <flux:sidebar.header>
            <x-app-logo
                :sidebar="true"
                href="{{ route('dashboard') }}"
                wire:navigate
            />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav
            collapsible="true"
            persist
        >
            <flux:sidebar.group
                :heading="__('dashboard::strings.Platform')"
                class="grid"
            >
                <flux:sidebar.item
                    icon="home"
                    :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate
                >
                    {{ __('dashboard::strings.Dashboard') }}
                </flux:sidebar.item>
                <flux:sidebar.group
                    expandable
                    :heading="__('car::strings.Cars')"
                    class="grid"
                    :expanded="request()->routeIs('cars.*')"
                >
                    <flux:sidebar.item
                        icon="car"
                        :href="route('cars.index')"
                        :current="request()->routeIs('cars.index')"
                        wire:navigate
                    >
                        {{ __('car::strings.Cars List') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item
                        icon="car"
                        icon:trailing="plus"
                        :href="route('cars.add')"
                        :current="request()->routeIs('cars.add')"
                        wire:navigate
                    >
                        {{ __('car::strings.Add Car') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group
                    expandable
                    :heading="__('car::strings.Colors')"
                    class="grid"
                    :expanded="request()->routeIs('colors.*')"
                >
                    <flux:sidebar.item
                        icon="swatch"
                        :href="route('colors.index')"
                        :current="request()->routeIs('colors.index')"
                        wire:navigate
                    >
                        {{ __('car::strings.Colors List') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item
                        icon="swatch"
                        icon:trailing="plus"
                        :href="route('colors.add')"
                        :current="request()->routeIs('colors.add')"
                        wire:navigate
                    >
                        {{ __('car::strings.Add Color') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group
                    expandable
                    :heading="__('user::strings.Users')"
                    class="grid"
                    :expanded="request()->routeIs('users.*')"
                >
                    <flux:sidebar.item
                        icon="user"
                        :href="route('users.list')"
                        :current="request()->routeIs('users.list')"
                        wire:navigate
                    >
                        {{ __('user::strings.Users List') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item
                        icon="user"
                        :href="route('users.add')"
                        icon:trailing="plus"
                        :current="request()->routeIs('users.add')"
                        wire:navigate
                    >
                        {{ __('user::strings.Add User') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                <flux:sidebar.group
                    expandable
                    :heading="__('order::strings.Orders')"
                    class="grid"
                    :expanded="request()->routeIs('orders.*')"
                >
                    <flux:sidebar.item
                        icon="shopping-cart"
                        :href="route('orders.list')"
                        :current="request()->routeIs('orders.list')"
                        wire:navigate
                    >
                        {{ __('order::strings.Orders') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.group>
            <flux:sidebar.group
                expandable
                :heading="__('car::strings.Car Companies')"
                class="grid"
                :expanded="request()->routeIs('companies.*')"
            >
                <flux:sidebar.item
                    icon="building-office"
                    :href="route('companies.index')"
                    :current="request()->routeIs('companies.index')"
                    wire:navigate
                >
                    {{ __('car::strings.Car Companies List') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="building-office"
                    icon:trailing="plus"
                    :href="route('companies.add')"
                    :current="request()->routeIs('companies.add')"
                    wire:navigate
                >
                    {{ __('car::strings.Add Car Company') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item
                icon="folder-git-2"
                href="https://github.com/laravel/livewire-starter-kit"
                target="_blank"
            >
                {{ __('Repository') }}
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="book-open-text"
                href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank"
            >
                {{ __('Documentation') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <x-dashboard::desktop-user-menu
            class="hidden lg:block"
            :name="auth()->user()->fullName()"
            :initials="auth()->user()->initials()"
        />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle
            class="lg:hidden"
            icon="bars-2"
            inset="left"
        />

        <flux:spacer />

        <flux:dropdown
            position="top"
            align="end"
        >
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
            />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item
                        :href="route('profile.edit')"
                        icon="cog"
                        wire:navigate
                    >
                        {{ __('dashboard::strings.Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="w-full"
                >
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                    >
                        {{ __('dashboard::strings.Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts

</body>

</html>
