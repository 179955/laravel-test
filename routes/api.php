<?php

use App\Http\Controllers\ContactFormController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/contact-form', ContactFormController::class)->name('contact-form');
