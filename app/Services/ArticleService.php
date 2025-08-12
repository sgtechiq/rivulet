<?php
namespace App\Services;

use App\Models\Authors;

class ArticleService
{
    public static function getAuthorName($authorId)
    {
        $author = Authors::find($authorId);
        return $author ? $author->getAttribute('name') : 'Unknown';
    }
}
