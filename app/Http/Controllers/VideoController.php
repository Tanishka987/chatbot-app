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

    try {
        // Send the video file to the external API
        $response = Http::attach(
            'video', file_get_contents($videoFile->getPathname()), $videoFile->getClientOriginalName()
        )->post('http://139.84.172.91:5000/Detect');

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
