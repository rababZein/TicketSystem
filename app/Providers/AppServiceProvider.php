<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\PermissionObserver;
use App\Models\Permission;
use App\Observers\ProjectObserver;
use App\Models\Project;
use App\Observers\ReceiptObserver;
use App\Models\Receipt;
use App\Observers\RoleObserver;
use App\Models\Role;
use App\Observers\TaskObserver;
use App\Models\Task;
use App\Observers\TicketObserver;
use App\Models\Ticket;
use App\Observers\UserObserver;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Role::observe(RoleObserver::class);
        Permission::observe(PermissionObserver::class);
        Project::observe(ProjectObserver::class);
        Tiket::observe(TiketObserver::class);
        Task::observe(TaskObserver::class);
        Receipt::observe(ReceiptObserver::class);  
    }
}
