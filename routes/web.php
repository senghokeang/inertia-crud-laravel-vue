<?php
// Inertia CRUD - laravelcenter.com

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::controller(CustomerController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('edit/{id}',  'edit');
    Route::match(['post', 'put'], 'submit', 'submit');
    Route::delete('delete/{id}', 'delete');
});
