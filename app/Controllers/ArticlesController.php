<?php
namespace App\Controllers;

use App\Models\Articles;
use Rivulet\Controller;

class ArticlesController extends Controller
{
    public function listArticles()
    {
        return jsonSuccess(Articles::all());
    }

    public function listByAuthor($authorId)
    {
        $articles = Articles::where('author_id', $authorId)->get();
        return jsonSuccess($articles);
    }

    public function listByDate()
    {
        $data = $this->request->input();
        $this->validate($data, ['start' => 'required|date', 'end' => 'required|date']);
        $articles = Articles::where('created_at', '>=', $data['start'])->where('created_at', '<=', $data['end'])->get();
        return jsonSuccess($articles);
    }

    public function listByDateAndAuthor()
    {
        $data = $this->request->input();
        $this->validate($data, ['author_id' => 'required|integer', 'start' => 'required|date', 'end' => 'required|date']);
        $articles = Articles::where('author_id', $data['author_id'])->where('created_at', '>=', $data['start'])->where('created_at', '<=', $data['end'])->get();
        return jsonSuccess($articles);
    }

    public function countArticles()
    {
        $articlesModel = new Articles();
        return jsonSuccess($articlesModel->query()->getCount());
    }

    public function countByAuthor($authorId)
    {
        $articlesModel = new Articles();
        return jsonSuccess($articlesModel->where('author_id', $authorId)->getCount());
    }

    public function countByDate()
    {
        $data = $this->request->input();
        $this->validate($data, ['start' => 'required|date', 'end' => 'required|date']);
        return jsonSuccess(Articles::where('created_at', '>=', $data['start'])->where('created_at', '<=', $data['end'])->getCount());
    }

    public function countByDateAndAuthor()
    {
        $data = $this->request->input();
        $this->validate($data, ['author_id' => 'required|integer', 'start' => 'required|date', 'end' => 'required|date']);
        return jsonSuccess(Articles::where('author_id', $data['author_id'])->where('created_at', '>=', $data['start'])->where('created_at', '<=', $data['end'])->getCount());
    }

    public function addArticle()
    {
        $data = $this->request->input();
        $this->validate($data, ['title' => 'required', 'content' => 'required', 'author_id' => 'required|integer']);
        $data['slug'] = article_slug($data['title']);
        $article      = Articles::create($data);
        LogMessage('Article added: ' . $article->getAttribute('id'), 'info');
        // Cache template if used
        PutCache('email_template_add', $this->view('email.add', ['article' => $article]), 3600);
        app()->make('mail')->to('aahn87@proton.me')->subject('Article Added')->view('email.add', ['article' => $article])->send();
        return jsonSuccess($article);
    }

    public function deleteArticle($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $article = Articles::find($id);
        if (! $article) {
            return jsonError('Article not found', 404);
        }

        $article->delete(false);
        LogMessage('Article deleted: ' . $id, 'warning');
        TriggerEvent('App\Events\ArticleDeleted', ['id' => $id]);
        return jsonSuccess('Article deleted');
    }

    public function editArticle($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $article = Articles::find($id);
        if (! $article) {
            return jsonError('Article not found', 404);
        }

        $data = $this->request->input();
        if (isset($data['title'])) {
            $data['slug'] = article_slug($data['title']);
        }

        $article->fill($data);
        $article->save();
        LogMessage('Article edited: ' . $id, 'info');
        app()->make('queue')->push('App\Jobs\SendUpdateEmailJob', ['id' => $id]);
        return jsonSuccess($article);
    }
}
