<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\VideoController;
/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [VideoController::class, 'index']);
Route::get('/app', function () {
    return view('app'); 
});
Route::post('/upload', [VideoController::class, 'upload'])->name('video.upload');
Route::post('/api/chat', [ChatbotController::class, 'handleChat']);
Route::post('/api/chat/delete', [ChatbotController::class, 'deleteChatHistory']);
