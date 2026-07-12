{{--
    Select tunggal — wrapper tipis di atas <x-form.field>. Semua logika
    label/error/hint ada di form/field.blade.php, jangan diduplikasi di sini.

    Pemakaian:
        <x-form.select name="peran" label="Peran">
            <option value="guru">Guru</option>
            <option value="kepala_sekolah">Kepala Sekolah</option>
        </x-form.select>

    Props: label, name, error, hint (diteruskan ke <x-form.field>).
    Slot: opsi <option> dari pemanggil. Atribut lain diteruskan ke <select>.
--}}
@props([
    'label' => null,
    'name' => null,
    'error' => null,
    'hint' => null,
])

<x-form.field :label="$label" :name="$name" :error="$error" :hint="$hint">
    <select {{ $attributes->merge(['id' => $name, 'name' => $name, 'class' => 'form-control']) }}>
        {{ $slot }}
    </select>
</x-form.field>
