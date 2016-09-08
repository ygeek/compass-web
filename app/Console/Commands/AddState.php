<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AdministrativeArea;

class AddState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addstate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'addstate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $root_area = AdministrativeArea::where('name', '美国')->first();
        $name = $root_area->name;

        $node = AdministrativeArea::create(['name' => '华盛顿哥伦比亚特区']);
        $root_area->appendNode($node);

        echo 'ok';
    }
}
