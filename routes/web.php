<?php

use Illuminate\Support\Facades\Auth;
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


Route::get('/', 'HomeController@index')->name('/');
Route::get('/place/data', 'DataController@places')->name('place.data'); // DATA TABLE CONTROLLER
Route::post('/tugas/delete', 'TugasController@delete');

Route::get('/kuisioner/view/{url}', 'KuisionerUmrahController@view')->name('kuisioner.umrah.view');
Route::post('/kuisioner/save/{kuisionerumrah_id}/{umrah_id}', 'KuisionerUmrahController@saveKuisionerUmrah')->name('kuisioner.umrah.save');
Route::get('/kuisioner/success', 'KuisionerUmrahController@kuisionerSuccess')->name('kuisioner.umrah.success');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('dashboard')->group(function () {
    Route::get('/kuisioner','DashboardController@dashboardKuisioner')->name('dashbaord.kuisioner');
    Route::get('/analytics','DashboardController@dashboardAnalitycs')->name('dashbaord.analytics');
    Route::get('/analytics/sop_n/detail/{id}','AktivitasUmrahController@detailSopNByAktivitasUmrah');
    Route::get('/resumekuisioner','DashboardController@resumeKuisioner')->name('dashbaord.resume.kuisioner');
    Route::get('/resumekuisioner/detail/','DashboardController@getDetailResumeByTourcode')->name('dashbaord.resume.kuisioner.detail');
    Route::get('/resumekuisionerkategori/detail', 'DashboardController@getdataKuisioner');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('places', 'PlaceController');

    Route::group(['prefix' =>  'master'], function(){
    
        Route::group(['prefix' =>  'aspirator'], function(){
            Route::get('/', 'UmrahController@index')->name('aspirator.index');
           
        });
        
        Route::group(['prefix' => 'kategori-aspirasi'], function(){
            Route::get('/','KuisionerController@index')->name('kategori-aspirasi.index');
            
        });
    
        Route::group(['prefix' =>  'status-aspirasi'], function(){
            Route::get('/', 'PembimbingController@index')->name('status-aspirasi.index');
            
        });

        Route::group(['prefix' =>  'urgensi-aspirasi'], function(){
            Route::get('/', 'PembimbingController@index')->name('urgensi-aspirasi.index');
            
        });
        
    
        Route::group(['prefix' =>  'user'], function(){
           
            // PROFILE
            Route::get('/myprofile', 'UserController@myProfile')->name('user.myprofile');
            Route::post('/myprofile/update/{user_id}', 'UserController@updateProfile')->name('user.updateprofile');
    
        });
        
    });
});
