<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\StackController;
use App\Http\Controllers\API\V1\KvController;

Route::middleware('api')->prefix('v1')->group(function () {

    // --- Stack (LIFO) ---
    Route::post('/stack/add',   [StackController::class, 'push']);   // Add to stack
    Route::get('/stack/get',    [StackController::class, 'pop']);    // Get from stack (and remove)

    // --- Key–Value store ---
    Route::post('/kv/add',      [KvController::class, 'set']);       // Add to key-value store (optional TTL)
    Route::get('/kv/get/{key}',     [KvController::class, 'get']);       // Get from key-value store by key
    Route::delete('/kv/delete', [KvController::class, 'delete']);    // Delete from key-value store
});
