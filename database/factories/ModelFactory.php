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
        'phone_number' => $faker->phoneNumber,
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
        'state_id' => function(){
            return factory(App\State::class)->create()->id;
        }
    ];
});

$factory->define(App\State::class, function (Faker\Generator $faker){
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

$factory->define(App\Examination::class, function(){
    $examination_names = ['高考', '雅思', '托福'];

    return [
        'name' => array_rand($examination_names)
    ];
});

$factory->define(\App\Speciality::class, function (){
    $speciality_names = collect(['机械工程', '电子信息工程', '土木工程', '计算数学', '建筑经济', '金融']);
    $name = $speciality_names->random();
    return [
        'name' => $name,
        'category_id' => \App\SpecialityCategory::all()->random()->id,
        'degree_id' => \App\Degree::all()->random()->id
    ];
});

$factory->define(App\College::class, function(Faker\Generator $faker){
    if(App\AdministrativeArea::count() == 0){
        foreach (config('adminstrative_area') as $root_node){
            $node = App\AdministrativeArea::create($root_node);

            $node->save();
        }
    }
    
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
        'administrative_area_id' => function(){
            return App\AdministrativeArea::whereIsRoot()->get()->first()->id;
        },
    ];
});
