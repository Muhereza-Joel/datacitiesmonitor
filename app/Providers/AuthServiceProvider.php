<?php

namespace App\Providers;

use App\Models\Archive;
use App\Models\AreaOfFocus;
use App\Models\Files;
use App\Models\Indicator;
use App\Models\Project;
use App\Models\Report;
use App\Models\ReportArea;
use App\Models\Response;
use App\Models\TheoryOfChange;
use App\Models\User;
use App\Policies\ArchivePolicy;
use App\Policies\AreaOfFocusPolicy;
use App\Policies\FilePolicy;
use App\Policies\IndicatorPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ReportAreaPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ResponsePolicy;
use App\Policies\TheoriesOfChangePolicy;
use App\Policies\UserPolicy;
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
        Files::class => FilePolicy::class,
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        AreaOfFocus::class => AreaOfFocusPolicy::class,
        Report::class => ReportPolicy::class,
        ReportArea::class => ReportAreaPolicy::class,
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
