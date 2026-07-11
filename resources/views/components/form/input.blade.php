{{--
    Input teks tunggal — wrapper tipis di atas <x-form.field>. Semua logika
    label/error/hint ada di form/field.blade.php, jangan diduplikasi di sini.

    Pemakaian:
        <x-form.input name="email" label="Email" type="email" placeholder="nama@sekolah.id" />

    Props: label, name, error, hint (diteruskan ke <x-form.field>).
    Atribut lain (type, placeholder, value, wire:model, dst) diteruskan ke <input>.
--}}
@props([
    'label' => null,
    'name' => null,
    'error' => null,
    'hint' => null,
])

<x-form.field :label="$label" :name="$name" :error="$error" :hint="$hint">
    <input {{ $attributes->merge(['type' => 'text', 'id' => $name, 'name' => $name, 'class' => 'form-control']) }}>
</x-form.field>
