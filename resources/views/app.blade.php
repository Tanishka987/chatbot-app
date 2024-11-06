<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        #app {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #chatbox {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            background-color: #fafafa;
        }
        .message {
            margin: 10px 0;
        }
        .user {
            font-weight: bold;
            color: #007bff;
        }
        .bot {
            font-weight: bold;
            color: #28a745;
        }
        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            margin-top: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="app">
        <h1>Chatbot</h1>
        <div id="chatbox"></div>
        <input type="text" id="userInput" placeholder="Type your message..." />
        <button id="sendButton">Send</button>
    </div>

    <script>
        document.getElementById('sendButton').addEventListener('click', async function() {
            const userInput = document.getElementById('userInput');
            const message = userInput.value.trim();

            if (message === '') return;

           
            displayMessage('User ', message);

  
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message }),
            });

            const data = await response.json();
            displayMessage('Bot', data.response);

            
            userInput.value = '';
        });

        function displayMessage(sender, message) {
            const chatbox = document.getElementById('chatbox');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.innerHTML = `<span class="${sender.toLowerCase()}">${sender}:</span> ${message}`;
            chatbox.appendChild(messageDiv);
            chatbox.scrollTop = chatbox.scrollHeight; 
        }
    </script>
</body>
</html>