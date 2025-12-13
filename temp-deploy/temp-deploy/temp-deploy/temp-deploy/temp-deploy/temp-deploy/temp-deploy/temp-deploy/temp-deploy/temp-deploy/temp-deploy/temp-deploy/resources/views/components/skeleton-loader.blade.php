@props(['type' => 'card', 'count' => 1])

@if($type === 'card')
    @for($i = 0; $i < $count; $i++)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border-2 border-gray-200 dark:border-gray-700 animate-pulse">
        <div class="flex items-start gap-3 mb-3">
            <!-- Avatar skeleton -->
            <div class="w-11 h-11 bg-gray-300 dark:bg-gray-600 rounded-lg shrink-0"></div>
            <div class="flex-1">
                <!-- Name skeleton -->
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                <!-- Badges skeleton -->
                <div class="flex gap-2">
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-20"></div>
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
                </div>
            </div>
        </div>
        <!-- Date skeleton -->
        <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/2 mb-3"></div>
        <!-- Button skeleton -->
        <div class="h-10 bg-gray-300 dark:bg-gray-600 rounded"></div>
    </div>
    @endfor
@elseif($type === 'table-row')
    @for($i = 0; $i < $count; $i++)
    <tr class="animate-pulse">
        <td class="px-6 py-4">
            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-8"></div>
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                <div class="flex-1">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-32 mb-2"></div>
                    <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-24"></div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-20"></div>
        </td>
        <td class="px-6 py-4">
            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-24"></div>
        </td>
        <td class="px-6 py-4">
            <div class="flex gap-2">
                <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded"></div>
                <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded"></div>
            </div>
        </td>
    </tr>
    @endfor
@elseif($type === 'stats')
    @for($i = 0; $i < $count; $i++)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border-2 border-gray-200 dark:border-gray-700 animate-pulse">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gray-300 dark:bg-gray-600 rounded-xl"></div>
            <div class="h-10 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
        </div>
        <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded w-24 mb-2"></div>
        <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-32"></div>
    </div>
    @endfor
@endif
