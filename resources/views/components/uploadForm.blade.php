{{-- resources/views/components/uploadForm.blade.php --}}
@props(['onSubmit'])

@php
    $videoFile = null;
    $dragActive = false;
@endphp

<form 
    id="uploadForm"
    class="form-container"
    ondragenter="event.preventDefault(); setDragActive(true);"
    ondragleave="event.preventDefault(); setDragActive(false);"
    ondragover="event.preventDefault(); setDragActive(true);"
    ondrop="handleDrop(event);"
    onsubmit="handleSubmit(event);"
>
    <div class="file-upload-container">
        <div class="file-upload-box">
            <svg class="file-upload-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <label class="file-name-label" id="file-name-label">
                {{ $videoFile ? $videoFile->getClientOriginalName() : 'Drop your video here or click to upload' }}
            </label>
            <p class="file-instruction">
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
                class="select-video-button"
            >
                Select Video
            </label>
        </div>
    </div>
    <button
        type="submit"
        id="submit-button"
        disabled="{{ !$videoFile ? 'disabled' : '' }}"
        class="submit-button"
    >
        Detect Fake Video
    </button>
</form>

<script>
    let videoFile = null;
    let dragActive = false;

    function setDragActive(active) {
        dragActive = active;
        document.getElementById('uploadForm').classList.toggle('drag-active', dragActive);
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
            const formData = new FormData();
            formData.append('video', videoFile);
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

<style>
    .form-container {
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 2rem;
    }

    .file-upload-container {
        margin-bottom: 1.5rem;
    }

    .file-upload-box {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s;
    }

    .file-upload-box.drag-active {
        border-color: #3b82f6;
    }

    .file-upload-icon {
        margin-bottom: 1rem;
        height: 48px;
        width: 48px;
        color: #6b7280;
    }

    .file-name-label {
        display: block;
        font-size: 1.25rem;
        color: #4b5563;
        margin-bottom: 0.5rem;
    }

    .file-instruction {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1.25rem;
    }

    .select-video-button {
        display: inline-block;
        background-color: #eff6ff;
        color: #3b82f6;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .select-video-button:hover {
        background-color: #dbeafe;
    }

    .submit-button {
        width: 100%;
        padding: 1rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        color: white;
        background-color: #2563eb;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-button:disabled {
        background-color: #9ca3af;
        cursor: not-allowed;
    }

    .submit-button:not(:disabled):hover {
        background-color: #1d4ed8;
    }
</style>