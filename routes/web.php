<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\BackController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', [PagesController::class, 'index'])->name('homepage');
Route::post('/', [PagesController::class, 'feedback'])->name('feedback');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'handleAdmin'])->middleware('admin')->name('admin.route');

Route::prefix('admin')->group(function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'handleAdmin']);
    //Manage Admins
    Route::get('/user/admins', [BackController::class, 'admin_index'])->name('admin.admin_index');
    Route::get('/user/admin/create', [BackController::class, 'admin_create'])->name('admin.admin_create');
    Route::post('/user/admins', [BackController::class, 'admin_store'])->name('admin.admin_store');
    //manage users
    Route::get('/users', [BackController::class, 'user_index'])->name('admin.user_index');
    Route::get('/users/{id}/edit', [BackController::class, 'user_edit'])->name('admin.user_edit');
    Route::put('/users/{id}', [BackController::class, 'user_update'])->name('admin.user_update');
    //Feedback
    Route::get('/feedback', [BackController::class, 'feedback_index'])->name('admin.feedback');
    Route::delete('/feedback/{id}', [BackController::class, 'feedback_destroy'])->name('admin.feedback_destroy');
    Route::get('/feedback/{id}', [BackController::class, 'feedback_show'])->name('admin.feedback_show');
    //Book Category
    Route::get('/book/type', [BackController::class, 'book_type'])->name('admin.book_type');
    Route::get('/book/type/create', [BackController::class, 'type_create'])->name('admin.type_create');
    Route::post('/book/type', [BackController::class, 'type_store'])->name('admin.type_store');
    Route::delete('/book/type/{id}', [BackCOntroller::class, 'type_destroy'])->name('admin.type_destroy');
    //Book CRUD
    Route::get('/book', [BackController::class, 'book_index'])->name('admin.book_index');
    Route::get('/book/create', [BackController::class, 'book_create'])->name('admin.book_create');
    Route::post('/book', [BackController::class, 'book_store'])->name('admin.book_store');
    Route::delete('/book/{id}', [BackController::class, 'book_destroy'])->name('admin.book_destroy');
    Route::get('/book/{id}/edit', [BackController::class, 'book_edit'])->name('admin.book_edit');
    Route::put('/book/{id}', [BackController::class, 'book_update'])->name('admin.book_update');
    Route::get('/book/{id}', [BackController::class, 'book_show'])->name('admin.book_show');

});

//User Route
Route::prefix('user')->group(function(){
    //Book Published
    Route::get('/book', [UserController::class, 'book_index'])->name('user.book_index');
    Route::get('/book/create', [UserController::class, 'book_create'])->name('user.book_create');
    Route::post('/book', [UserController::class, 'book_store'])->name('user.book_store');
    Route::get('/book/{id}', [UserController::class, 'book_show'])->name('user.book_show');
    Route::get('/book/{id}/edit', [UserController::class, 'book_edit'])->name('user.book_edit');
    Route::put('/book/{id}', [UserController::class, 'book_update'])->name('user.book_update');
    Route::delete('/book/{id}', [UserController::class, 'book_destroy'])->name('user.book_destroy');
});



//Visitor Route
Route::get('/book/{id}', [PagesController::class, 'book_show']);
Route::get('/books', [PagesController::class, 'book_index'])->name('visitor.book_index');
//search the book
Route::post('/books', [PagesController::class, 'book_find'])->name('book.search');