<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArticleTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateArticle()
    {
        //测试创建全站文章(全站文章无college_id)
        $category = new \App\ArticleCategory();
        $category->name = '留学攻略';

        $this->assertTrue($category->save());

        $article = new \App\Article();
        $article->title = '呵呵呵呵这是一条留学攻略';
        $article->content = 'https://www.google.com';

        $article->category_id = $category->id;
        $this->assertTrue($article->save());
    }
}
