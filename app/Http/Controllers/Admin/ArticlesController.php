<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\ArticleCategory;
use App\College;
use Illuminate\Http\Request;

use App\Http\Requests;

class ArticlesController extends BaseController
{
    public function pictureUpload(Request $request){
        $image = $request->file('image');
        if($image){
            $result = app('qiniu_uploader')->upload_file($image);
            return $this->okResponse($result);
        }else{
            return $this->errorResponse('没有上传图片');
        }
    }

    public function index(Request $request){
        $college_id = $request->input('college_id');
        $college = College::find($college_id);

        $article_categories = ArticleCategory::all();

        $articles_query = Article::with('category');
        if($college_id){
            $articles_query = $articles_query->where('college_id', $college_id);
        }else{
            $articles_query = $articles_query->whereNull('college_id');
        }

        $articles = $articles_query->paginate(15);
        
        return view('admin.articles.index', compact('article_categories', 'articles', 'college', 'college_id'));
    }

    public function create(Request $request){
        $article = new Article();
        $article_categories = ArticleCategory::all();
        $college_id = $request->input('college_id');

        return view('admin.articles.create', compact('article_categories', 'article', 'college_id'));
    }

    public function edit($article_id, Request $request){
        $article = Article::find($article_id);
        $article_categories = ArticleCategory::all();
        $college_id = $request->input('college_id');
        return view('admin.articles.edit', compact('article_categories', 'article', 'college_id'));

    }

    public function update($article_id, Request $request){
        $article = Article::find($article_id);
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->category_id = $request->input('category_id');
        $article->order_weight = $request->input('order_weight', 0);

        if($article->save()){
            $college_id = $article->college_id;
            return redirect()->route('admin.articles.index', ['college_id' => $college_id]);
        }
    }

    public function store(Request $request){
        $college_id = $request->input('college_id', null);
        $article = new Article();
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->category_id = $request->input('category_id');
        $article->college_id = $college_id;
        $article->order_weight = $request->input('order_weight', 0);

        if($article->save()){
            return redirect()->route('admin.articles.index',  ['college_id' => $college_id]);
        }
    }

    public function destroy($article_id){
        $article = Article::find($article_id);
        $college_id = $article->college_id;
        $article->delete();
        return redirect()->route('admin.articles.index',  ['college_id' => $college_id]);
    }
}
