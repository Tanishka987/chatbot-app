<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detect Fake Videos with AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Vite directive -->
</head>
<body class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-center text-4xl font-bold text-gray-900 mb-4">Detect Fake Videos with AI</h1>
        <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">
            Upload your video to analyze it for potential manipulation using our advanced AI detection system.
        </p>

        <div class="max-w-xl mx-auto">
            <div id="alert" class="hidden mb-6"></div>
            <form id="uploadForm" action="{{ route('video.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-gray-700">Upload Video</span>
                        <input type="file" name="video" accept="video/*" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500" required />
                    </label>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Analyze Video
                    </button>
                </div>
            </form>
            <div id="resultDisplay" class="mt-8 p-6 bg-white shadow-lg rounded-lg text-center hidden">
                <div id="resultIcon" class="flex items-center justify-center mb-4"></div>
                <h2 id="resultText" class="text-2xl font-bold mb-2"></h2>
                <div id="confidenceBar" class="mt-4 bg-gray-100 rounded-full h-4 overflow-hidden">
                    <div id="confidenceFill" class="h-full"></div>
                </div>
                <p id="confidenceText" class="text-gray-600 mt-2"></p>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('uploadForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const alertDiv = document.getElementById('alert');
        const resultDisplay = document.getElementById('resultDisplay');
        const resultIcon = document.getElementById('resultIcon');
        const resultText = document.getElementById('resultText');
        const confidenceFill = document.getElementById('confidenceFill');
        const confidenceText = document.getElementById('confidenceText');

        alertDiv.classList.add('hidden');
        resultDisplay.classList.add('hidden');

        try {
            const response = await fetch(event.target.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to process video');
            }

            const data = await response.json();
            const isFake = data.output.toLowerCase().includes('fake');

            resultIcon.innerHTML = isFake ? '<svg class="h-12 w-12 text-red-500"><path d="..."/></svg>' : '<svg class="h-12 w-12 text-green-500"><path d="..."/></svg>';
            resultText.textContent = data.output;
            confidenceFill.className = `h-full ${isFake ? 'bg-red-500' : 'bg-green-500'}`;
            confidenceFill.style.width = `${data.confidence}%`;
            confidenceText.textContent = `Confidence: ${data.confidence.toFixed(2)}%`;

            resultDisplay.classList.remove('hidden');
        } catch (error) {
            alertDiv.classList.remove('hidden');
            alertDiv.innerHTML = `<div class="p-4 rounded-md border bg-red-50 text-red-700 border-red-200">${error.message}</div>`;
        }
    });
</script>
</body>
</html>