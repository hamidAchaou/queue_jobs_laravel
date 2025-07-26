<?php

use App\Http\Controllers\ProfileController;
use App\Mail\MyTestEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/send-test-email', function () {
    // Set recipient email address
    $toEmail = 'hamidachaou379@gmail.com';

    // Send the email
    Mail::to($toEmail)->send(new MyTestEmail());

    return 'Test email sent successfully!';
});


require __DIR__ . '/auth.php';
