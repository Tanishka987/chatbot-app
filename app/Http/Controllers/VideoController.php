<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function upload(Request $request)
{
    $request->validate([
        'video' => 'required|file|mimes:mp4,avi,mov|max:20480', // Max size 20MB
    ]);

    $videoFile = $request->file('video');

    // Prepare the file for the request
    $formData = [
        'video' => fopen($videoFile->getPathname(), 'r'),
        'filename' => $videoFile->getClientOriginalName(),
    ];

    try {
        // Send the video file to the external API
        $response = Http::post('http://127.0.0.1:3000/Detect', $formData);

        if ($response->failed()) {
            throw new \Exception('Failed to process video');
        }

        // Get the result from the response
        $data = $response->json();

        return response()->json($data);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error processing video: ' . $e->getMessage()], 500);
    }
}
}