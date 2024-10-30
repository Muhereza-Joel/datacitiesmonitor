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
        $userAgentDetails = UserActionLog::parseUserAgent(request()->header('User-Agent'));
        $ipDetails = UserActionLog::getIpDetails(request()->ip());

        UserActionLog::create([
            'user_id' => $event->user->id,
            'action' => $event->action,
            'ip_address' => request()->ip(),
            'hostname' => $ipDetails['hostname'] ?? null,
            'city' => $ipDetails['city'] ?? null,
            'region' => $ipDetails['region'] ?? null,
            'country' => $ipDetails['country'] ?? null,
            'loc' => $ipDetails['loc'] ?? null,
            'org' => $ipDetails['org'] ?? null,
            'timezone' => $ipDetails['timezone'] ?? null,
            'resource_type' => $event->resourceType,
            'resource_id' => $event->resourceId,
            'device_os' => $userAgentDetails['os'],
            'device_architecture' => $userAgentDetails['architecture'],
            'device_browser' => $userAgentDetails['browser'],
        ]);
    }
}
