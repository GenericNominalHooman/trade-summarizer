<?php

use App\Livewire\Note;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('dashboard2', 'dashboard2')
    ->middleware(['auth'])  
    ->name('dashboard2');

Route::get('/note', Note::class)->middleware(['auth'])->name('note');
// Route::view('note', 'livewire.note')
//     ->middleware(['auth'])
//     ->name('note');
    
require __DIR__.'/auth.php';