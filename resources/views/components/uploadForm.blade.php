{{-- resources/views/components/uploadForm.blade.php --}}
@props(['onSubmit'])

@php
    $videoFile = null;
    $dragActive = false;
@endphp

<form 
    id="uploadForm"
    class="bg-white shadow-lg rounded-lg p-8 {{ $dragActive ? 'border-2 border-blue-500' : '' }}"
    ondragenter="event.preventDefault(); setDragActive(true);"
    ondragleave="event.preventDefault(); setDragActive(false);"
    ondragover="event.preventDefault(); setDragActive(true);"
    ondrop="handleDrop(event);"
    onsubmit="handleSubmit(event);"
>
    <div class="mb-6">
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <label class="block text-gray-700 text-lg font-medium mb-2" id="file-name-label">
                {{ $videoFile ? $videoFile->getClientOriginalName() : 'Drop your video here or click to upload' }}
            </label>
            <p class="text-sm text-gray-500 mb-4">
                Supports MP4, MOV, AVI formats
            </p>
            <input
                type="file"
                onchange="handleFileChange(event)"
                accept="video/*"
                class="hidden"
                id="video-upload"
            />
            <label
                for="video-upload"
                class="inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-md cursor-pointer hover:bg-blue-100 transition-colors"
            >
                Select Video
            </label>
        </div>
    </div>
    <button
        type="submit"
        id="submit-button"
        disabled="{{ !$videoFile ? 'disabled' : '' }}"
        class="w-full py-3 px-4 rounded-md font-medium text-white transition-colors
            {{ $videoFile ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed' }}"
    >
        Detect Fake Video
    </button>
</form>

<script>
    let videoFile = null;
    let dragActive = false;

    function setDragActive(active) {
        dragActive = active;
        document.getElementById('uploadForm').classList.toggle('border-2 border-blue-500', dragActive);
    }

    function handleDrop(e) {
        e.preventDefault();
        setDragActive(false);

        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('video/')) {
            videoFile = file;
            document.getElementById('file-name-label').innerText = videoFile.name;
            document.getElementById('submit-button').disabled = false;
        }
    }

    function handleFileChange(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('video/')) {
            videoFile = file;
            document.getElementById('file-name-label').innerText = videoFile.name;
            document.getElementById('submit-button').disabled = false;
        }
    }

    function handleSubmit(e) {
        e.preventDefault();
        if (videoFile) {
            // You can implement your form submission logic here, e.g., using AJAX or a form submission
            // For example, you can use FormData to send the video file to the server
            const formData = new FormData();
            formData.append('video', videoFile);
            // Replace 'your-upload-url' with the actual URL to handle the upload
            fetch('your-upload-url', {
                method: 'POST',
                body: formData
            }).then(response => {
                // Handle response
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    }
</script>