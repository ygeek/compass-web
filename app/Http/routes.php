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

    Route::get('/country_degree_examination_map', 'Admin\CountryDegreeExaminationMapController@index')
        ->name('country_degree_examination_map');

    //Resources
    Route::resource('colleges', 'Admin\CollegesController');
    Route::resource('examination_score_weights', 'Admin\ExaminationScoreWeightsController');
    //--

    Route::get('/examination_score_weights/{weight_id}/colleges', 'Admin\ExaminationScoreWeightsController@colleges')
        ->name('admin.examination_score_weights.colleges');

    Route::patch('/examination_score_weights/{weight_id}/colleges', 'Admin\ExaminationScoreWeightsController@updateColleges')
        ->name('admin.examination_score_weights.updateColleges');
});
