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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::middleware(['checkAdmin'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/index', 'PasienController@index');
            Route::get('/create','PasienController@create');  
            Route::post('/add','PasienController@add');  
            Route::get('/{id}/delete','PasienController@delete');  
            Route::get('/{id}/edit','PasienController@edit');  
            Route::post('/editpost','PasienController@editPost');  
            Route::get('/{id}/viewDetail', 'PasienController@index');  
            Route::get('/{id}/tambah','PasienController@tambah');

            Route::group(['prefix'=>'kategori'], function(){
                Route::get('/index', 'KategoriController@index');  
                Route::get('/create', 'KategoriController@tambah');  
                Route::get('/{idKategori}/edit', 'KategoriController@edit');  
                Route::get('/{idKategori}/delete', 'KategoriController@delete');  
                Route::post('/editpost', 'KategoriController@editPost');  
                Route::post('/add', 'KategoriController@add');  
            });

            Route::group(['prefix'=>'rentang'], function(){
                Route::get('/{idKategori}/index', 'RentangController@index');  
                Route::get('/{idKategori}/create', 'RentangController@tambah');  
                Route::get('/{idRentang}/delete', 'RentangController@delete');  
                Route::get('/{idRentang}/edit', 'RentangController@edit');  
                Route::post('/edit', 'RentangController@editPost');  
                Route::post('/add', 'RentangController@add');  
            });
            

            Route::group(['prefix'=>'user-management'], function(){
                Route::get('/',['as'=>'admin.user.index', 'uses'=>'UserManagementController@index']);
                Route::post('/add',['as'=>'admin.user.add', 'uses'=>'UserManagementController@add']);
                Route::get('/{id}/delete',['as'=>'admin.user.delete', 'uses'=>'UserManagementController@delete']);
            });
        });
    });
    
    
    Route::get('/populate', 'PasienController@populateTable');  
    Route::get('/caripola', 'PasienController@cariPola');  
    Route::post('/caripola', 'PasienController@cariPolaPost');  
    
    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});
