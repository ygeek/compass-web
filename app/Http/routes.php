<?php

Route::get('/', 'IndexController@index');

//评估
Route::get('/estimate/step-1', 'EstimateController@stepFirst')->name('estimate.step_first');
Route::get('/estimate/step-2', 'EstimateController@stepSecond')->name('estimate.step_second');
Route::post('/estimate', 'EstimateController@store')->name('estimate.store');
Route::group(['prefix' => 'home'], function (){
    Route::get('/', 'HomeController@index')->name('home.index');
    Route::get('/messages', 'HomeController@messages')->name('home.messages');
    Route::patch('/messages/{message_id}', 'HomeController@readMessage')->name('home.messages.read');
});


Route::group(['prefix' => 'auth'], function (){
    Route::post('verify-codes', 'Auth\AuthController@createVerifyCodes')->name('auth.verifyCode.store');
    Route::post('register', 'Auth\AuthController@register')->name('auth.users.store');
    Route::post('login', 'Auth\AuthController@login')->name('auth.login');
});


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
    Route::resource('colleges.specialities', 'Admin\SpecialitiesController');
    Route::resource('colleges.examination_score_map', 'Admin\CollegeExaminationScoreMapController');
    Route::resource('examination_score_weights', 'Admin\ExaminationScoreWeightsController');
    Route::resource('messages', 'Admin\MessagesController');
    //--

    Route::get('setting/{key}', 'Admin\SettingController@index')->name('admin.setting.index');
    Route::post('setting/{key}', 'Admin\SettingController@store')->name('admin.setting.store');

    Route::get('requirement/{type}/{id}', 'Admin\RequirementController@index')->name('admin.requirement.index');
    Route::post('requirement/{type}/{id}', 'Admin\RequirementController@store')->name('admin.requirement.store');

    Route::get('/examination_score_weights/{weight_id}/colleges', 'Admin\ExaminationScoreWeightsController@colleges')
        ->name('admin.examination_score_weights.colleges');

    Route::patch('/examination_score_weights/{weight_id}/colleges', 'Admin\ExaminationScoreWeightsController@updateColleges')
        ->name('admin.examination_score_weights.updateColleges');
});
