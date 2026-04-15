<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 font-sans text-sm" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
               <x-icon name="gauge" style="duotone" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
               <span class="ms-3 font-poppins">Dashboard</span>
            </a>
         </li>

         @foreach(\Nwidart\Modules\Facades\Module::allEnabled() as $module)
            @php
                $nameLower = strtolower($module->getName());
                // Skip modules that shouldn't appear in the main sidebar
                if(in_array($nameLower, ['homepage', 'relatorios', 'notificacoes'])) continue;

                $routeFound = Route::has($nameLower . '.index');
            @endphp
            <li>
               <a href="{{ $routeFound ? route($nameLower . '.index') : '#' }}"
                  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $routeFound && request()->routeIs($nameLower . '.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} {{ !$routeFound ? 'opacity-50 cursor-not-allowed' : '' }}">
                  <x-icon module="{{ $module->getName() }}" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                  <span class="flex-1 ms-3 whitespace-nowrap font-poppins">{{ $module->getName() }}</span>
               </a>
            </li>
         @endforeach
      </ul>
   </div>
</aside>
