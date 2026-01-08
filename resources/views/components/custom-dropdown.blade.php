@props([
    'name',
    'id' => null,
    'value' => '',
    'options' => [],
    'placeholder' => '-- Pilih --',
    'required' => false,
    'wireModel' => null,
    'class' => '',
    'icon' => 'expand_more'
])

@php
    $id = $id ?? $name;
    $selectedOption = collect($options)->firstWhere('value', $value);
    $selectedLabel = $selectedOption ? $selectedOption['label'] : $placeholder;
    $selectedIcon = $selectedOption['icon'] ?? null;
@endphp

<div class="relative custom-dropdown-container {{ $class }}" 
     x-data="{ 
        open: false, 
        value: '{{ $value }}', 
        label: '{{ $selectedLabel }}',
        icon: '{{ $selectedIcon }}',
        select(val, lbl, ico) {
            this.value = val;
            this.label = lbl;
            this.icon = ico;
            this.open = false;
            $refs.hiddenInput.value = val;
            $refs.hiddenInput.dispatchEvent(new Event('change'));
            @if($wireModel)
                $wire.set('{{ $wireModel }}', val);
            @endif
        }
     }"
     @click.outside="open = false">
    
    <input type="hidden" 
           name="{{ $name }}" 
           id="{{ $id }}" 
           x-ref="hiddenInput"
           value="{{ $value }}"
           @if($required) required @endif
           @if($wireModel) wire:model="{{ $wireModel }}" @endif>

    <button type="button" 
            @click="open = !open"
            class="dropdown-button w-full px-3 sm:px-4 py-2 sm:py-3 text-left border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all flex items-center justify-between">
        <span class="dropdown-label flex items-center gap-2" :class="!value ? 'text-gray-400 dark:text-gray-500' : ''">
            <span x-text="label"></span>
        </span>
        <span class="material-symbols-outlined text-gray-400 transition-transform duration-200 dropdown-arrow"
              :style="open ? 'transform: rotate(180deg)' : 'transform: rotate(0deg)'">{{ $icon }}</span>
    </button>

    <div class="dropdown-menu-custom absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-50 transition-all duration-200 origin-top"
         x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         style="display: none;">
        <div class="p-1.5 space-y-1">
            @foreach($options as $option)
                <div class="dropdown-item px-4 py-2.5 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3"
                     :class="value === '{{ $option['value'] }}' ? 'active' : ''"
                     @click="select('{{ $option['value'] }}', '{{ $option['label'] }}', '{{ $option['icon'] ?? '' }}')">
                    @if(isset($option['icon']))
                        <span class="material-symbols-outlined text-lg">{{ $option['icon'] }}</span>
                    @endif
                    {{ $option['label'] }}
                </div>
            @endforeach
        </div>
    </div>
</div>
