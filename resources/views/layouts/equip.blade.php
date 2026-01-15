{{-- resources/views/layouts/equip.blade.php --}}
<x-app-layout>
   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @yield('title')
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
