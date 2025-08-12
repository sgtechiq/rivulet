<?php
namespace App\Models;

use Rivulet\Model;

class Articles extends Model
{
    protected $table    = 'articles';
    protected $fillable = ['title', 'slug', 'content', 'author_id'];
    protected $guarded  = ['id'];

    public function author()
    {
        return $this->belongsTo('App\Models\Authors', 'author_id');
    }
}
