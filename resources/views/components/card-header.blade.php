{{-- V1: header kartu seragam — netral dengan aksen kiri primary, menggantikan
     header berwarna solid/gradient acak yang dulu disalin lintas halaman --}}
@props(['title'])
<div class="border-l-4 border-l-primary-600 border-b border-b-gray-200 dark:border-b-gray-700 bg-gray-50 dark:bg-gray-800/60 px-4 py-3 sm:px-6 sm:py-4">
    <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
</div>
