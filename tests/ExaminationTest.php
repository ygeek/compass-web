<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExaminationTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateExamination(){
      $examination = new \App\Examination();

      $score_sections = [
          'key' => 'value',
          'content' => 'hehe'
      ];

      $examination->score_sections = $score_sections;

      $examination->name = 'hello';
      $examination->save();
      $examination = \App\Examination::find($examination->id);
      $this->assertEquals($score_sections, $examination->score_sections);
    }
}
