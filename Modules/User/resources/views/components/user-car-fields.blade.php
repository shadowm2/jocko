<flux:field>
    <flux:label>
        {{ __('car::strings.Car Company') }}
    </flux:label>

    <flux:select wire:model.live="form.car_company">

        <flux:select.option value="">
            {{ __('car::strings.Select Company') }}
        </flux:select.option>

        @foreach ($companies as $company)
            <flux:select.option :value="$company->slug">
                {{ $company->name }}
            </flux:select.option>
        @endforeach

    </flux:select>

    <flux:error name="form.car_company" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('car::strings.Car Name') }}
    </flux:label>

    <flux:select wire:model="form.car">

        <flux:select.option value="">
            {{ __('car::strings.Select Car') }}
        </flux:select.option>

        @foreach ($cars as $car)
            <flux:select.option :value="$car->slug">
                {{ $car->name }}
            </flux:select.option>
        @endforeach

    </flux:select>

    <flux:error name="form.car" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('user::attributes.Manufactured At') }}
    </flux:label>

    <input
        class="persian-datepicker
                    appearance-none [:where(&)]:w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-[1.375rem]
                    rounded-lg shadow-xs border ebg-white dark:bg-white/10 dark:disabled:bg-white/[7%]
                    text-zinc-700 dark:text-zinc-300 disabled:text-zinc-500 dark:disabled:text-zinc-400
                    has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400
                    dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border
                    border-zinc-200 border-b-zinc-300/80 dark:border-white/10"
        wire:model="form.manufactured_at"
        value="{{ $this->form->manufactured_at_gregorian }}"
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
