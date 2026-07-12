{{--
    Wrapper TUNGGAL label/error/hint untuk kontrol form. Jangan duplikasi
    markup label/error ini di komponen form lain — form/input|select|textarea
    membungkus dirinya di komponen ini dan hanya menaruh elemen kontrolnya di slot.

    Pemakaian:
        <x-form.field label="Nama" name="nama" hint="Sesuai KTP">
            <input name="nama" class="form-control">
        </x-form.field>

    Props:
        - label (string, opsional)
        - name (string, opsional): dipakai untuk `for` label dan fallback pesan error.
        - error (string, opsional): jika kosong, jatuh ke $errors->first($name).
        - hint (string, opsional): teks bantuan, disembunyikan saat ada error.
--}}
@props([
    'label' => null,
    'name' => null,
    'error' => null,
    'hint' => null,
])

@php
    $errorMessage = $error ?? (isset($errors) && $name ? $errors->first($name) : null);
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ $label }}</label>
    @endif

    {{ $slot }}

    @if($hint && ! $errorMessage)
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $hint }}</p>
    @endif

    @if($errorMessage)
        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $errorMessage }}</p>
    @endif
</div>
