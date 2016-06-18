<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Admin::class, function (Faker\Generator $faker){
    return [
        'username' => $faker->email,
        'password' => bcrypt($faker->password),
        'name' => $faker->name
    ];
});

$factory->define(App\City::class, function (Faker\Generator $faker){
    return [
        'name' => $faker->city,
        'region_id' => function(){
            return factory(App\Region::class)->create()->id;
        }
    ];
});

$factory->define(App\Region::class, function (Faker\Generator $faker){
    return [
        'name' => $faker->state,
        'country_id' => function(){
            return factory(App\Country::class)->create()->id;
        }
    ];
});

$factory->define(App\Country::class, function (Faker\Generator $faker){
   return [
       'name' => $faker->country,
   ];
});

$factory->define(App\College::class, function(Faker\Generator $faker){
    return [
        'chinese_name' => '悉尼大学',
        'english_name' => 'University of Sydney',
        'description' => '悉尼大学的历史可以追溯到1848年，当时的新南威尔士名流威廉·温特沃斯（William Wentworth）
        在立法会议上提议将1830年建立的悉尼学院（Sydney College）扩大成一所大学。',
        'telephone_number' => '+61 2 9351 2222',
        'founded_in' => '1850',
        'address' => '澳大利亚悉尼',
        'website' => 'http://sydney.edu.au/',
        'qs_ranking' => 1,
        'us_new_ranking' => 2,
        'times_ranking' => 3,
        'domestic_ranking' => 1,
        'badge_path' => 'http://sydney.edu.au/etc/designs/corporate-commons/bower_components/corporate-frontend/dist/assets/img/USydLogo.svg',
        'background_image_path' => null,
        'city_id' => function(){
            return factory(App\City::class)->create()->id;
        }
    ];
});
