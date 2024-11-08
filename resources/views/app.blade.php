<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot Application</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1a202c, #2d3748);
            margin: 0;
            padding: 0;
            height: 100vh;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .cont {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100%;
        }
        #app {
            max-width: 600px;
            width: 100%;
            background: #ffffff;
            padding: 50px;
            height: 60%;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: stretch;
            position: relative;
        }

        /* Hamburger Menu Styling */
        .hamburger {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .history-item {
            background: #495057;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            word-wrap: break-word;
        }
        
        /* Chat App Styling */
        h1 {
            color: #343a40;
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        #chatbox {
            flex-grow: 1;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 15px;
        }
        .message {
            margin: 12px 0;
            padding: 10px 15px;
            border-radius: 8px;
            max-width: 80%;
            word-wrap: break-word;
            font-size: 0.95rem;
        }
        .user {
            background-color: #007bff;
            color: #fff;
            text-align: right;
            align-self: flex-end;
        }
        .bot {
            background-color: #28a745;
            color: #fff;
            align-self: flex-start;
        }
        #userInput {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .button-container {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 12px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            flex: 1;
            color: white;
            transition: background-color 0.3s;
        }
        #sendButton {
            background-color: #007bff;
        }
        #sendButton:hover {
            background-color: #0056b3;
        }
        #deleteButton {
            background-color: #dc3545;
        }
        #deleteButton:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="cont">
        <div class="sidebar" id="sidebar">
            <h2>Chat History</h2>
            <div id="historyContainer"></div>
        </div>

        <div id="app">
            <span class="hamburger" id="hamburger">&#9776;</span>
            <h1>Chatbot</h1>
            <div id="chatbox"></div>
            <input type="text" id="userInput" placeholder="Type your message..." />
            <div class="button-container">
                <button id="sendButton">Send</button>
                <button id="deleteButton">Delete Chat History</button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toggle sidebar
        document.getElementById('hamburger').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        document.getElementById('sendButton').addEventListener('click', async function() {
            const userInput = document.getElementById('userInput');
            const message = userInput.value.trim();
            
            if (message === '') return;

            displayMessage('User', message);

            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message: message }),
            });

            const data = await response.json();
            displayMessage('Bot', data.response);
            updateHistory(message, data.response);

            userInput.value = '';
        });
        
        document.getElementById('deleteButton').addEventListener('click', async function() {
            const response = await fetch('/api/chat/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, 
                },
            });

            const data = await response.json();
            alert(data.message);
            document.getElementById('chatbox').innerHTML = '';
            document.getElementById('historyContainer').innerHTML = '';
        });

        function displayMessage(sender, message) {
            const chatbox = document.getElementById('chatbox');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', sender.toLowerCase());
            messageDiv.innerHTML = `<span>${message}</span>`;
            chatbox.appendChild(messageDiv);
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        function updateHistory(userMessage, botResponse) {
            const historyContainer = document.getElementById('historyContainer');

            const userDiv = document.createElement('div');
            userDiv.classList.add('history-item', 'user');
            userDiv.textContent = `User: ${userMessage}`;

            const botDiv = document.createElement('div');
            botDiv.classList.add('history-item', 'bot');
            botDiv.textContent = `Bot: ${botResponse}`;

            historyContainer.appendChild(userDiv);
            historyContainer.appendChild(botDiv);
            historyContainer.scrollTop = historyContainer.scrollHeight;
        }
    </script>
</body>
</html>
