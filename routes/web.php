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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'cuti'],function(){
    Route::get('','CutiController@index')->name('cuti.index');
    Route::get('/data','CutiController@data')->name('cuti.data');
    Route::get('/karyawan','CutiController@getKaryawan')->name('cuti.karyawan');
    Route::post('/store','CutiController@store')->name('cuti.store');
    Route::get('/edit/{id}','CutiController@edit')->name('cuti.edit');
    Route::put('/update/{id}','CutiController@update')->name('cuti.update');
    Route::delete('/delete/{id}','CutiController@delete')->name('cuti.delete');
});


Route::group(['prefix' => 'pengajuan'],function(){
    Route::get('/','PengajuanCutiController@index')->name('pengajuan.index');
    Route::post('/store','PengajuanController@store')->name('pengajuan.store');
    Route::get('/edit/{id}','PengajuanController@edit')->name('pengajuan.edit');
    Route::put('/update/{id}','PengajuanController@update')->name('pengajuan.update');
    Route::delete('/delete/{id}','PengajuanController@delete')->name('pengajuan.delete');
});
