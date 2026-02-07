<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [PollController::class, 'index'])->name('polls.index');
    Route::get('/polls/{poll}', [PollController::class, 'show'])->name('polls.show');

    Route::get('/api/polls/{poll}/results', [PollController::class, 'results'])->name('polls.results');
    Route::post('/api/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');

    Route::get('/admin/polls/{poll}/ips', [PollController::class, 'ipList'])->name('polls.ips');
    Route::post('/admin/polls/{poll}/release', [PollController::class, 'releaseIp'])->name('polls.release');
});
