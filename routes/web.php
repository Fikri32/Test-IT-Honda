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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/mark', 'HomeController@markNotification')->name('mark.home');

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
    Route::get('/data','PengajuanCutiController@data')->name('pengajuan.data');
    Route::get('/getCuti','PengajuanCutiController@getCuti')->name('pengajuan.cuti');
    Route::get('/getstatus','PengajuanCutiController@getStatus')->name('pengajuan.status');
    Route::post('/store','PengajuanCutiController@store')->name('pengajuan.store');
    Route::get('/edit/{id}','PengajuanCutiController@edit')->name('pengajuan.edit');
    Route::put('/update/{id}','PengajuanCutiController@update')->name('pengajuan.update');
    Route::delete('/delete/{number}','PengajuanCutiController@delete')->name('pengajuan.delete');
});

Route::group(['prefix' => 'persetujuan'],function(){
    Route::get('/','PersetujuanCutiController@index')->name('persetujuan.index');
    Route::get('/data','PersetujuanCutiController@data')->name('persetujuan.data');
    Route::get('/detail/{id}','PersetujuanCutiController@detail')->name('persetujuan.detail');
    Route::put('/accept/{id}','PersetujuanCutiController@accept')->name('persetujuan.accept');
});

Route::get('/number','PengajuanCutiController@generateNumber');
