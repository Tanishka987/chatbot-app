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
        // Validate the video file
        $request->validate([
            'video' => 'required|file|mimes:mp4,avi,mov|max:40480', // Max size 20MB
        ]);
    
        $videoFile = $request->file('video');
    
        try {
            // Log the incoming file info for debugging
            \Log::info('Video file uploaded', [
                'file_name' => $videoFile->getClientOriginalName(),
                'file_size' => $videoFile->getSize(),
                'file_type' => $videoFile->getMimeType(),
            ]);
    
            $response = Http::timeout(300)  
                ->attach(
                    'video', file_get_contents($videoFile->getPathname()), $videoFile->getClientOriginalName()
                )
                ->post('http://139.84.172.91:5000/Detect');
    
            // Log the response for debugging
            \Log::info('Response from external API', [
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
    
            // Check for failure in the response
            if ($response->failed()) {
                throw new \Exception('Failed to process video');
            }
    
            // Get the result from the response
            $data = $response->json();
    
            // Log the data from the response
            \Log::info('Processed video result', [
                'response_data' => $data
            ]);
    
            // Return the result
            return response()->json($data);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error processing video', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
    
            // Return the error response
            return response()->json(['error' => 'Error processing video: ' . $e->getMessage()], 500);
        }
    }
    
}