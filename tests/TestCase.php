<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';
    protected $faker;

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp(){
        parent::setUp();
        $this->faker = Faker\Factory::create();
        $this->faker->addProvider(new Faker\Provider\zh_CN\PhoneNumber($this->faker));
        $this->faker->addProvider(new Faker\Provider\zh_CN\Person($this->faker));
        $this->faker->addProvider(new Faker\Provider\zh_CN\Company($this->faker));
        $this->faker->addProvider(new Faker\Provider\zh_CN\Address($this->faker));
        $this->faker->addProvider(new Faker\Provider\Internet($this->faker));
    }
}
