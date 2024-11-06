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
                'model' => 'zephyr-7b-beta-Q5_K_M', 
                'messages' => [
                    [
                        'role' => 'user', 
                        'content' => $message, 
                    ],
                ],
                'max_tokens' => 512,
                'seed' => -1,
                'temperature' => 0.8,
                'top_k' => 40,
                'top_p' => 0.9,
                'stream' => true,
            ];

            
            $response = $client->post($this->vultrApiUrl . 'chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->vultrApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['response'] ?? 'Sorry, I did not understand that.';
        } catch (RequestException $e) {
        
            return 'Error: ' . $e->getMessage();
        }
    }
}