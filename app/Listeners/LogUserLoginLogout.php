<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Models\UserActionLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLoginLogout
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

    public function handleUserLoggedIn(UserLoggedIn $event)
    {
        $userAgentDetails = UserActionLog::parseUserAgent(request()->header('User-Agent'));
        $ipDetails = UserActionLog::getIpDetails(request()->ip());

        UserActionLog::create([
            'user_id' => $event->user->id,
            'action' => 'User logged in',
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

    public function handleUserLoggedOut(UserLoggedOut $event)
    {
        $userAgentDetails = UserActionLog::parseUserAgent(request()->header('User-Agent'));
        $ipDetails = UserActionLog::getIpDetails(request()->ip());

        UserActionLog::create([
            'user_id' => $event->user->id,
            'action' => 'User logged out',
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
