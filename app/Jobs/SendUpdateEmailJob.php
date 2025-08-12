<?php
namespace App\Jobs;

use Rivulet\Queue\Job;

class SendUpdateEmailJob extends Job
{
    public function handle()
    {
        $id = $this->data['id'];
        app()->make('mail')->to('aahn87@proton.me')->subject('Article Updated')->text("Article {$id} updated")->send();
        LogMessage('Update email sent for article ' . $id, 'info');
    }
}
