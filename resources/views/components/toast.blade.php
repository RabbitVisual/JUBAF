<div x-data="{ show: false, message: '', type: 'info' }"
     x-on:toast.window="show = true; message = $event.detail.message; type = $event.detail.type || 'info'; setTimeout(() => show = false, 3000)"
     class="fixed bottom-4 right-4 z-50 transition-all duration-300 transform"
     x-show="show"
     x-transition:enter="translate-y-2 opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="opacity-100"
     x-transition:leave-end="opacity-0">

    <div :class="{
        'bg-green-50 text-green-800 border-green-200': type === 'success',
        'bg-red-50 text-red-800 border-red-200': type === 'error',
        'bg-blue-50 text-blue-800 border-blue-200': type === 'info',
        'bg-yellow-50 text-yellow-800 border-yellow-200': type === 'warning'
    }" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 border" role="alert">
        <div class="ms-3 text-sm font-normal font-sans" x-text="message"></div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" @click="show = false" aria-label="Close">
            <span class="sr-only">Close</span>
            <x-icon name="duotone-xmark" class="w-3 h-3" />
        </button>
    </div>
</div>
