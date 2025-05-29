{{-- resources/views/components/feature-card.blade.php --}}
@props(['title', 'iconPathD', 'iconViewBox' => '0 0 24 24'])

<div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 group">
    <div class="flex items-center space-x-4 mb-4">
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-4 rounded-full text-white shadow-md group-hover:shadow-lg transition-shadow duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                 viewBox="{{ $iconViewBox }}" stroke="currentColor" stroke-width="2">
                <title>{{ $title }} Icon</title>
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPathD }}" />
            </svg>
        </div>
        <h3 class="text-2xl font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors duration-300">{{ $title }}</h3>
    </div>
    <p class="text-gray-600 leading-relaxed">{{ $slot }}</p>
</div>