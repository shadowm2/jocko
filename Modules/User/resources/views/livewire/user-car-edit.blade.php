@props(['onSubmit' => null])

<div>
    <x-user::user-car-form
        :companies="$companies"
        :cars="$cars"
        :onSubmit="fn(...$args) => call_user_func($onSubmit, ...$args)"
    />
</div>
