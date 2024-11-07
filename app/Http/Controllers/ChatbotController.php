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

    public function handleChat(Request $request)
    {
        $userMessage = $request->input('message');

        // Call the Vultr API to get a response
        $botResponse = $this->getBotResponse($userMessage);

        return response()->json(['response' => $botResponse]);
    }

    protected function getBotResponse($message)
    {
        $client = new Client();

        try {
            $requestData = [
                'model' => 'zephyr-7b-beta-Q5_K_M', // Model name as a string
                'messages' => [
                    [
                        'role' => 'user', // Role as a string
                        'content' => $message, // User's message as a string
                    ],
                ],
                'max_tokens' => 512,
                'seed' => -1,
                'temperature' => 0.8,
                'top_k' => 40,
                'top_p' => 0.9,
                'stream' => false, // Set to true if you want streaming responses
            ];
            $response = $client->post($this->vultrApiUrl . 'chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->vultrApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            $data = json_decode($response->getBody(), true);

            // Adjust this based on the actual response structure
            return $data['choices'][0]['message']['content'] ?? 'Sorry, I did not understand that.';
        } catch (RequestException $e) {
            // Log the error or handle it as needed
            return 'Error: ' . $e->getMessage();
        }
    }
}