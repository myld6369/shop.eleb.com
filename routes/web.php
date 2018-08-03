<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::resource('/users','UsersController');
Route::patch('/users/user/{user}','UsersController@user')->name('users.user');

Route::get('/users/pass/{user}','UsersController@pass')->name('users.pass');

Route::patch('/users/password/{user}','UsersController@password')->name('users.password');

Route::get('login', 'SessionsController@login')->name('login');
Route::post('login', 'SessionsController@store')->name('login.store');
Route::delete('logout', 'SessionsController@destroy')->name('logout');
Route::resource('/menucategories','MenuCategoriesController');
Route::resource('/menus','MenusController');
Route::resource('/activities','ActivityController');

Route::post('upload',function(){
$storage =\Illuminate\Support\Facades\Storage::disk('oss');
$fileName =$storage->putFile('upload',request()->file('file'));
return [
    'fileName'=>$storage->url($fileName),
];
})->name('upload');

Route::resource('/orders','OrderController');
Route::get('/sales','OrderController@sales')->name('orders.sales');
Route::get('/menussales','OrderController@menussales')->name('orders.menussales');
//Route::get('/showOrder{order}','OrderController@showOrder')->name('showOrder');
Route::resource('/events','EventController');
Route::get('/prize{event}','EventController@prize')->name('events.prize');