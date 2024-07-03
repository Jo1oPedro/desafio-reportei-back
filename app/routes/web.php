<?php

use App\Http\Controllers\AuthController;
use App\Jobs\VisitaJob;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/', function () {
    $key = "redis-welcome-views";
    $views = null;
    try {
        $redis = Redis::connection('default');
        $redis->incr($key, 1);
        $views = $redis->get($key, null);
    } catch (\Throwable $th) {
    }

    VisitaJob::dispatch(request()->ip())->delay(now()->addSeconds(15))->onQueue('high');

    return view('welcome')->with('views', $views);
});

Route::get('/documentation', function () {
    $filePath = public_path('api-documentation/index.html'); // Replace with your actual file path

    if (!Storage::disk('local')->exists($filePath)) {
        abort(404, 'File not found'); // Handle non-existent files gracefully
    }

    $fileContents = Storage::disk('local')->get($filePath);
    $mimeType = 'text/html'; // Set the MIME type explicitly for HTML

    $headers = [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'attachment; filename="api-documentation.html"', // Set attachment disposition for saving
    ];

    return response($fileContents, 200, $headers);
});
