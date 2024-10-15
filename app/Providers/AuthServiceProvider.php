<?php

namespace App\Providers;

use App\Models\Archive;
use App\Models\Indicator;
use App\Models\Response;
use App\Models\TheoryOfChange;
use App\Policies\ArchivePolicy;
use App\Policies\IndicatorPolicy;
use App\Policies\ResponsePolicy;
use App\Policies\TheoriesOfChangePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    
        Indicator::class => IndicatorPolicy::class,
        TheoryOfChange::class => TheoriesOfChangePolicy::class,
        Response::class => ResponsePolicy::class,
        Archive::class => ArchivePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
