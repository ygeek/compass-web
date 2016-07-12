<?php

use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{

    public function run()
    {
        $category_names = ['学校概况', '录取情况', '图片', '留学攻略', '语言学习', '移民攻略', '关于', '友情链接'];

        foreach ($category_names as $category_name){
            $category = new \App\ArticleCategory();
            $category->name = $category_name;
            $category->save();
        }
    }
}
