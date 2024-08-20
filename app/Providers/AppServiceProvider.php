<?php

namespace App\Providers;


use App\Http\Controllers\MemberController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Observers\ProjectObserver;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);

        Project::observe(ProjectObserver::class);

        Gate::define("showMembers", function (User $user, Project $project) {
            return $project->members->contains($user);
        });
        Gate::define("editMembers", function (User $user, Project $project) {
            return $project->user_id==$user->id;
        });
    }
}
