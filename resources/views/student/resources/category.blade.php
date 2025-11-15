@extends('layouts.app')

@section('title', $categories[$category] . ' - Mental Health Corner')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $categories[$category] }}</h1>
                <p class="text-gray-600 mt-2">Browse all resources in this category</p>
            </div>
            <a href="{{ route('mhc') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Mental Health Corner
            </a>
        </div>
    </div>

    <!-- Resources Grid -->
    @if($resources->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 text-lg">No resources available in this category yet.</p>
            <p class="text-gray-400 mt-2">Check back later for new content.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($resources as $resource)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                    <!-- Resource Image Container - Larger -->
                    <div class="relative h-64 bg-gray-100 overflow-hidden flex-shrink-0">
                        <img src="{{ $resource->image_url }}"
                             alt="{{ $resource->title }}"
                             class="w-full h-full object-contain p-6 bg-white">

                        <!-- Icon Overlay -->
                        <div class="absolute top-4 right-4">
                            <i class="{{ $resource->icon }} text-gray-700 text-2xl bg-white bg-opacity-90 rounded-full p-4 shadow-lg"></i>
                        </div>


                    </div>

                    <!-- Resource Content -->
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-gray-800 mb-3 leading-tight">{{ $resource->title }}</h3>

                        <!-- Full Description - No Clamping -->
                        <div class="mb-3 flex-grow">
                            <p class="text-gray-600 leading-relaxed text-sm whitespace-pre-line">
                                {{ $resource->description }}
                            </p>
                        </div>

                        <!-- Disclaimer Section - Expandable -->
                        @if($resource->show_disclaimer)
                            <div class="mb-3">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-2 cursor-pointer"
                                     onclick="toggleDisclaimer({{ $resource->id }})">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-circle text-amber-500 text-xs mr-2"></i>
                                            <span class="text-amber-800 text-xs font-semibold">DISCLAIMER</span>
                                        </div>
                                        <i class="fas fa-chevron-down text-amber-600 text-xs transition-transform" id="disclaimer-icon-{{ $resource->id }}"></i>
                                    </div>
                                    <div id="disclaimer-content-{{ $resource->id }}" class="hidden mt-2">
                                        <p class="text-amber-700 text-xs leading-relaxed whitespace-pre-line">
                                            {{ $resource->display_disclaimer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        @if($resource->link)
                            <a href="{{ $resource->link }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 flex items-center justify-center font-semibold shadow-md hover:shadow-lg mt-auto">
                                <span class="mr-2">{{ $resource->button_text }}</span>
                                <i class="fas fa-external-link-alt text-sm"></i>
                            </a>
                        @else
                            <button class="w-full bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 py-3 rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-300 font-semibold shadow-md mt-auto">
                                {{ $resource->button_text }}
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    /* Smooth image loading */
    img {
        transition: transform 0.3s ease;
    }

    .bg-white img:hover {
        transform: scale(1.05);
    }

    /* Ensure consistent card heights and proper text wrapping */
    .grid > div {
        display: flex;
        flex-direction: column;
        min-height: 650px; /* Increased minimum height for larger images */
    }

    /* Preserve line breaks in description */
    .whitespace-pre-line {
        white-space: pre-line;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Make sure content area grows properly */
    .flex-grow {
        flex-grow: 1;
    }
</style>

<script>
    function toggleDisclaimer(resourceId) {
        const content = document.getElementById(`disclaimer-content-${resourceId}`);
        const icon = document.getElementById(`disclaimer-icon-${resourceId}`);

        content.classList.toggle('hidden');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    }
</script>
@endsection
