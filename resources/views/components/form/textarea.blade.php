{{--
    Textarea tunggal — wrapper tipis di atas <x-form.field>. Semua logika
    label/error/hint ada di form/field.blade.php, jangan diduplikasi di sini.

    Pemakaian:
        <x-form.textarea name="catatan" label="Catatan" rows="5">{{ old('catatan') }}</x-form.textarea>

    Props: label, name, error, hint (diteruskan ke <x-form.field>).
    Slot: isi teks awal textarea. Atribut lain (rows, placeholder, dst) diteruskan ke <textarea>.
--}}
@props([
    'label' => null,
    'name' => null,
    'error' => null,
    'hint' => null,
])

<x-form.field :label="$label" :name="$name" :error="$error" :hint="$hint">
    <textarea {{ $attributes->merge(['id' => $name, 'name' => $name, 'class' => 'form-control', 'rows' => 4]) }}>{{ $slot }}</textarea>
</x-form.field>
