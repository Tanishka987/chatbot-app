{{-- resources/views/components/navbar.blade.php --}}
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <span class="text-xl font-bold text-gray-900">Video Detector</span>
            </div>
            <div class="flex items-center">
                {{-- You can add links here using Laravel's route helper --}}
                <a class="text-gray-900 hover:text-gray-700">Home</a>
                <a class="ml-4 text-gray-900 hover:text-gray-700">About</a>
                <a  class="ml-4 text-gray-900 hover:text-gray-700">Contact</a>
            </div>
        </div>
    </div>
</nav>