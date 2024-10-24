<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\UnverifiedEmailNotification;

class SendUnverifiedEmailNotifications extends Command
{
    protected $signature = 'notifications:send-unverified';
    protected $description = 'Send notifications to users with unverified emails';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get users with unverified emails
        $unverifiedUsers = User::whereNull('email_verified_at')->get();

        foreach ($unverifiedUsers as $user) {
            // Send notification
            $user->notify(new UnverifiedEmailNotification());
        }

        $this->info('Notifications sent to unverified users.');
    }
}
