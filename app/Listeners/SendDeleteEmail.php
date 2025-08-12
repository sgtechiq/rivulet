<?php
namespace App\Listeners;

use Rivulet\Events\Event;
use Rivulet\Events\Listener;

class SendDeleteEmail extends Listener
{
    public function handle(Event $event): void
    {
        $id = $event->getData()['id'];
        app()->make('mail')->to('aahn87@proton.me')->subject('Article Deleted')->text("Article {$id} deleted")->send();
        LogMessage('Delete email sent for article ' . $id, 'info');
    }
}
