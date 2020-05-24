<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', 'LandingPageController@index')->name('index');
Route::post('/fetch', 'LandingPageController@fetch')->name('autocomplete.fetch');
Route::get('/shop', 'ShopController@index')->name('shop.index'); 
Route::get('/shop/{product}', 'ShopController@show')->name('shop.show');

Route::get('/cart', 'CartController@index')->name('cart.index');
Route::post('/cart', 'CartController@store')->name('cart.store');

Route::delete('/cart/{product}', 'CartController@destroy')->name('cart.destroy');
Route::patch('/cart/{product}', 'CartController@update')->name('cart.update');



Route::post('/cart/switchToSaveForLater/{product}', 'CartController@switchToSaveForLater')->name('cart.switchToSaveForLater');



Route::delete('/saveForLater/{product}', 'SaveForLater@destroy')->name('saveForLater.destroy');
Route::post('/saveForLater/switchToSaveForLater/{product}', 'SaveForLater@switchToCart')
->name('saveForLater.switchToCart');

Route::post('/coupon', 'CouponsController@store')->name('coupon.store');
Route::delete('/coupon', 'CouponsController@destroy')->name('coupon.destroy');

Route::get('/checkout', 'RaveController@index')->name('checkout.index')->middleware('auth');
Route::post('/checkout', 'RaveController@store')->name('checkout.store');

Route::post('/pay', 'RaveController@initialize')->name('pay');
Route::patch('/pay/offline', 'RaveController@offline')->name('cash.offline');
Route::get('/rave/callback', 'RaveController@callback')->name('callback');

Route::get('/pay', function(){
    dd('error');
});

Route::get('/search', 'ShopController@search')->name('search');

Route::get('/guestCheckout', 'CheckoutController@index')->name('guestCheckout.index');

Route::get('/thankyou', 'ThankYouController@index')->name('thankyou.index');
Route::get('empty','CartController@empty');

Route::get('/contact', 'ContactController@index');



Route::middleware('auth')->group(function(){
    Route::get('/my-profile', 'UsersController@edit')->name('users.edit');
    Route::patch('/my-profile', 'UsersController@update')->name('users.update');
    Route::get('/my-orders', 'OrdersController@index')->name('orders.index');
    Route::get('/my-orders/{order}', 'OrdersController@show')->name('orders.show');
});



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});






Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
