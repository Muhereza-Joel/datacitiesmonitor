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
            // Check if the user already has an unverified email notification
            $existingNotification = $user->notifications()
                ->where('type', UnverifiedEmailNotification::class)
                ->whereNull('read_at') // Optional: Only check for unread notifications
                ->exists();

            if (!$existingNotification) {
                // Send notification if none exists
                $user->notify(new UnverifiedEmailNotification());
            }
        }

        $this->info('Notifications sent to unverified users.');
    }
}
