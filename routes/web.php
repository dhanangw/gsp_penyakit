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

    Route::prefix('admin')->group(function () {
        Route::get('/index', 'PasienController@index');
        Route::get('/create','PasienController@create');  
        Route::post('/add','PasienController@add');  
        Route::get('/{id}/delete','PasienController@delete');  
        Route::get('/{id}/edit','PasienController@edit');  
        Route::post('/editpost','PasienController@editPost');  
        Route::get('/{id}/viewDetail', 'PasienController@index');  
        Route::get('/{id}/tambah','PasienController@tambah');  

        Route::group(['prefix'=>'user-management'], function(){
            Route::get('/',['as'=>'admin.user.index', 'uses'=>'UserManagementController@index']);
            Route::post('/add',['as'=>'admin.user.add', 'uses'=>'UserManagementController@add']);
            Route::get('/{id}/delete',['as'=>'admin.user.delete', 'uses'=>'UserManagementController@delete']);
        });
    });
    
    Route::get('/populate', 'PasienController@populateTable');  
    Route::get('/caripola', 'PasienController@cariPola');  
    Route::post('/caripola', 'PasienController@cariPolaPost');  
    
    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});
