<?php

Route::get('/', 'IndexController@index');
Route::group(['prefix' => 'auth'], function (){
    Route::post('verify-codes', 'Auth\AuthController@createVerifyCodes')->name('auth.verifyCode.store');
    Route::post('register', 'Auth\AuthController@register')->name('auth.users.store');
    Route::post('login', 'Auth\AuthController@login')->name('auth.login');
});

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin'], function(){
    //登陆相关
    Route::get('/', 'Admin\IndexController@index')->name('admin_home');
    Route::get('login', 'Admin\Auth\AuthController@getLogin');
    Route::post('login', 'Admin\Auth\AuthController@postLogin')->name('admin.auth.login');
    //--

    Route::get('/country_degree_examination_map', 'Admin\CountryDegreeExaminationMapController@index');

    //Resources
    Route::resource('colleges', 'Admin\CollegesController');
    Route::resource('examination_score_weights', 'Admin\ExaminationScoreWeightsController');
    //--
});
