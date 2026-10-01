@props(['row' => null])
<div>
    {{ \Illuminate\Support\Facades\Lang::has('dashboard::countries.' . $row->code)
        ? __('dashboard::countries.' . $row->code)
        : $row->name }}
</div>
