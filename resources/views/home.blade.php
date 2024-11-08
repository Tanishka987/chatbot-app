<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detect Fake Videos with AI</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1a202c, #2d3748);
            color: #f7fafc;
        }
        .cont {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 100px;
        }
        .container {
            width: 100%;
            max-width: 640px;
            padding: 2rem;
            box-sizing: border-box;
        }
        .title {
            text-align: center;
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .description {
            text-align: center;
            color: #a0aec0;
            margin-bottom: 2rem;
        }
        .form-container {
            background-color: #2d3748;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        .input-label {
            display: block;
            font-size: 1rem;
            color: #e2e8f0;
            margin-bottom: 0.5rem;
        }
        .file-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #4a5568;
            border-radius: 4px;
            font-size: 1rem;
            color: #f7fafc;
            background-color: #4a5568;
            box-sizing: border-box;
            margin-bottom: 1rem;
        }
        .submit-button {
            margin:0;
            width: 100%;
            padding: 0.75rem;
            background-color: #4299e1;
            color: #ffffff;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .submit-button:hover {
            background-color: #3182ce;
        }
        .alert, .result-display {
            display: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 8px;
        }
        .alert {
            background-color: #feb2b2;
            color: #742a2a;
            border: 1px solid #fc8181;
        }
        .result-display {
            text-align: center;
            background-color: #4a5568;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            margin-top: 20px;
        }
        .result-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .confidence-bar {
            height: 8px;
            background-color: #718096;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 1rem;
        }
        .confidence-fill {
            height: 100%;
        }
        .confidence-text {
            color: #a0aec0;
            margin-top: 0.5rem;
        }
        /* Loader styles */
        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #4299e1;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem auto;
            display: none;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    <div class="cont">
        <div class="container">
            <h1 class="title">Detect Fake Videos with AI</h1>
            <p class="description">
                Upload your video to analyze it for potential manipulation using our advanced AI detection system.
            </p>
            <div class="form-container">
                <div id="alert" class="alert"></div>
                 
                <form id="uploadForm" action="{{ route('video.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="input-label">Upload Video</label>
                    <input type="file" name="video" accept="video/*" class="file-input" required />
                    <button type="submit" class="submit-button">Analyze Video</button>
                </form>
                <div id="loader" class="loader"></div>
                <div id="resultDisplay" class="result-display">
                    <div id="resultIcon" class="result-icon"></div>
                    <h2 id="resultText"></h2>
                    <div class="confidence-bar">
                        <div id="confidenceFill" class="confidence-fill"></div>
                    </div>
                    <p id="confidenceText" class="confidence-text"></p>
                </div>
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
            const loader = document.getElementById('loader');

            // Hide previous results and show the loader
            alertDiv.style.display = 'none';
            resultDisplay.style.display = 'none';
            loader.style.display = 'block';

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

                // Display results
                resultIcon.innerHTML = isFake 
                    ? '<span style="color: #e53e3e;">&#10060;</span>' 
                    : '<span style="color: #38a169;">&#10004;</span>';
                resultText.textContent = data.output;
                confidenceFill.style.backgroundColor = isFake ? '#e53e3e' : '#38a169';
                confidenceFill.style.width = `${data.confidence}%`;
                confidenceText.textContent = `Confidence: ${data.confidence.toFixed(2)}%`;

                // Show the results and hide the loader
                resultDisplay.style.display = 'block';
            } catch (error) {
                alertDiv.style.display = 'block';
                alertDiv.textContent = error.message;
            } finally {
                // Hide the loader after request completes
                loader.style.display = 'none';
            }
        });
    </script>
</body>
</html>
