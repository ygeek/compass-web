<?php

Route::group(['prefix' => 'auth'], function (){
    Route::post('verify_codes', 'Auth\AuthController@createVerifyCodes');
    Route::post('register', 'Auth\AuthController@register');
    Route::post('login', 'Auth\AuthController@login');
});

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin'], function(){
    Route::get('/', 'Admin\IndexController@index')->name('admin_home');
    Route::get('login', 'Admin\Auth\AuthController@getLogin');
    Route::post('login', 'Admin\Auth\AuthController@postLogin');

    Route::resource('colleges', 'Admin\CollegesController');
});