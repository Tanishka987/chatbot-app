<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ChatbotController extends Controller
{
    protected $vultrApiKey;
    protected $vultrApiUrl;

    public function __construct()
    {
        $this->vultrApiKey = env('VULTR_API_KEY'); 
        $this->vultrApiUrl = 'https://api.vultrinference.com/v1/'; 
    }

    public function deleteChatHistory(Request $request)
    {
    // Clear the chat history from the session
    session()->forget('chat_history');

    // Return a response indicating success
    return response()->json(['message' => 'Chat history deleted successfully.']);
    }

    public function handleChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
    
        // Retrieve the user's message
        $userMessage = $request->input('message');
    
        // Retrieve conversation history from the session
        $history = session()->get('chat_history', []);
    
        // Add the user's message to the history (latest question first)
        array_unshift($history, ['role' => 'user', 'content' => $userMessage]);
    
        // Call the function to get a response from the AI
        $botResponse = $this->getBotResponse($history);
    
        // Add the bot's response to the history
        array_unshift($history, ['role' => 'assistant', 'content' => $botResponse]);
    
        // Save the updated history back to the session
        session()->put('chat_history', $history);
    
        // Return both the response and the history
        return response()->json(['response' => $botResponse, 'history' => $history]);
    }
    
    
    protected function getBotResponse($history)
    {
        $client = new Client();

        try {
            $requestData = [
                'model' => 'zephyr-7b-beta-Q5_K_M',
                'messages' => $history, // Use the entire conversation history
                'max_tokens' => 512,
                'seed' => -1,
                'temperature' => 0.8,
                'top_k' => 40,
                'top_p' => 0.9,
                'stream' => false,
            ];
            $response = $client->post($this->vultrApiUrl . 'chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->vultrApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['choices'][0]['message']['content'] ?? 'Sorry, I did not understand that.';
        } catch (RequestException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}