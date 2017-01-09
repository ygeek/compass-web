<?php

Route::get('/', 'IndexController@index')->name('index');
Route::get('/get_speciality', 'IndexController@getSpeciality')->name('index.get_speciality');

//评估
Route::get('/estimate/step-1', 'EstimateController@stepFirst')->name('estimate.step_first');
Route::get('/estimate/select-colleges', 'EstimateController@getCollegesForSelect')->name('estimate.select_colleges');
Route::get('/estimate/get_speciality', 'EstimateController@getSpeciality')->name('estimate.get_speciality');
Route::get('/estimate/step-2', 'EstimateController@stepSecond')->name('estimate.step_second');
Route::post('/estimate/stepSecondForm', 'EstimateController@stepSecondForm')->name('estimate.stepSecondFrom');
Route::post('/estimate/stepSecondPost', 'EstimateController@stepSecondPost')->name('estimate.stepSecondPost');
Route::post('/estimate', 'EstimateController@store')->name('estimate.store');
Route::resource('/intentions', 'IntentionsController');
Route::post('/intentions/create', 'IntentionsController@create')->name('intentions.create');

Route::post('/like_college', 'FavoritesController@store')->name('like.store');
Route::post('/dislike_college', 'FavoritesController@cancelFavorite')->name('like.destroy');

Route::get('/colleges', 'CollegesController@index')->name('colleges.index');
Route::get('/colleges_rank', 'CollegesController@rank')->name('colleges.rank');
Route::get('/colleges/{key}', 'CollegesController@show')->name('colleges.show');
Route::get('/hot_colleges', 'CollegesController@getRandomHotColleges')->name('colleges.hot_colleges');

Route::group(['prefix' => 'home'], function (){
    Route::get('/', 'HomeController@index')->name('home.index');
    Route::post('/', 'HomeController@saveProfile')->name('home.store_profile');
    Route::get('/messages', 'HomeController@messages')->name('home.messages');
    Route::get('/intentions', 'HomeController@intentions')->name('home.intentions');
    Route::get('/like-colleges', 'HomeController@likeColleges')->name('home.like_colleges');
    Route::patch('/messages/{message_id}', 'HomeController@readMessage')->name('home.messages.read');

    Route::post('/change_password', 'HomeController@changePassword')->name('home.change_password');
    Route::post('/change_phone', 'HomeController@changePhone')->name('home.change_phone');
});


Route::group(['prefix' => 'auth'], function (){
    Route::post('verify-codes', 'Auth\AuthController@createVerifyCodes')->name('auth.verifyCode.store');
    Route::post('register', 'Auth\AuthController@register')->name('auth.users.store');
    Route::get('logout', 'Auth\AuthController@logoutUser')->name('auth.logout_user');
    Route::post('login', 'Auth\AuthController@login')->name('auth.login');
    Route::get('reset-password', 'Auth\AuthController@resetPassword')->name('auth.reset_passwrod');
    Route::post('reset-password', 'Auth\AuthController@resetPassword')->name('auth.reset_passwrod');
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
    Route::get('articles/picture-upload', 'Admin\ArticlesController@getPictureUpload');
    Route::resource('colleges', 'Admin\CollegesController');
    Route::resource('colleges.specialities', 'Admin\SpecialitiesController');
    Route::resource('colleges.examination_score_map', 'Admin\CollegeExaminationScoreMapController');
    Route::resource('examination_score_weights', 'Admin\ExaminationScoreWeightsController');
    Route::resource('messages', 'Admin\MessagesController');
    Route::resource('articles', 'Admin\ArticlesController');
    Route::resource('intentions', 'Admin\IntentionsController');
    Route::post('intentions/{intentions}/export-to-excel', 'Admin\IntentionsController@exportToExcel')->name('admin.intentions.export_to_excel');
    Route::post('articles/picture-upload', 'Admin\ArticlesController@pictureUpload')->name('picture_upload');
    //--

    Route::get('setting/{key}', 'Admin\SettingController@index')->name('admin.setting.index');
    Route::post('setting/{key}', 'Admin\SettingController@store')->name('admin.setting.store');

    Route::get('requirement/{type}/{id}', 'Admin\RequirementController@index')->name('admin.requirement.index');
    Route::post('requirement/{type}/{id}', 'Admin\RequirementController@store')->name('admin.requirement.store');

    Route::get('/examination_score_weights/{weight_id}/colleges', 'Admin\ExaminationScoreWeightsController@colleges')
        ->name('admin.examination_score_weights.colleges');

    Route::patch('/examination_score_weights/{weight_id}/colleges', 'Admin\ExaminationScoreWeightsController@updateColleges')
        ->name('admin.examination_score_weights.updateColleges');

    Route::resource('advertisements', 'Admin\AdvertisementsController');
    Route::resource('users', 'Admin\UsersController');
});
