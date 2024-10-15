<?php

namespace App\Listeners;

use App\Models\UserActionLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserAction 
{
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        UserActionLog::create([
            'user_id' => $event->user->id,
            'action' => $event->action,
            'ip_address' => request()->ip(), // Get the user's IP address
            'resource_type' => $event->resourceType, // Include resource type
            'resource_id' => $event->resourceId, // Include resource ID
        ]);
    }
}
