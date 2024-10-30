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

        UserActionLog::create([
            'user_id' => $event->user->id,
            'action' => 'User logged in',
            'ip_address' => request()->ip(),
            'resource_type' => $event->resourceType,
            'resource_id' => $event->resourceId,
            'device_os' => $userAgentDetails['os'],
            'device_architecture' => $userAgentDetails['architecture'],
            'device_browser' => $userAgentDetails['browser'],
            'country' => UserActionLog::getCountryFromIp(request()->ip()),
        ]);
    }

    public function handleUserLoggedOut(UserLoggedOut $event)
    {
        $userAgentDetails = UserActionLog::parseUserAgent(request()->header('User-Agent'));
        
        UserActionLog::create([
            'user_id' => $event->user->id,
            'action' => 'User logged out',
            'ip_address' => request()->ip(),
            'resource_type' => $event->resourceType,
            'resource_id' => $event->resourceId,
            'device_os' => $userAgentDetails['os'],
            'device_architecture' => $userAgentDetails['architecture'],
            'device_browser' => $userAgentDetails['browser'],
            'country' => UserActionLog::getCountryFromIp(request()->ip()),
        ]);
    }
}
