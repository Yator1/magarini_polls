<?php

namespace App\Providers;

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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ///
        
        Gate::define('is_super_admin', function ($user) {
            return in_array($user->role->name, ['super_admin']);
        });
        Gate::define('is_admin', function ($user) {
            return in_array($user->role->name, ['admin', 'super_admin']);
        });
        Gate::define('is_staff', function ($user) {
            return in_array($user->role->name, ['staff']);
        });
        Gate::define('is_agent', function ($user) {
            return in_array($user->role->name, ['Agent']);
        });
        Gate::define('is_user', function ($user) {
            return in_array($user->role->name, ['user']);
        });
        Gate::define('add_poll', function ($user) {
            return in_array($user->role->name, ['staff','admin','Agent', 'super_admin']);
        });

        Gate::define('is_chief_officer', function ($user) {
            return in_array($user->role->name, ['chief_officer']);
        });

        Gate::define('is_chief_pharmacist', function ($user) {
            return in_array($user->role->name, ['chief_pharmacist']);
        });

        Gate::define('is_med_sup', function ($user) {
            return in_array($user->role->name, ['med_sup']);
        });
        
        Gate::define('is_supplier', function ($user) {
            return in_array($user->role->name, ['supplier']);
        });
        Gate::define('is_procurement_officer', function ($user) {
            return in_array($user->role->name, ['procurement_officer']);
        });

        Gate::define('is_storekeeper', function ($user) {
            return in_array($user->role->name, ['storekeeper']);
        });

        //groups
        Gate::define('admins', function ($user) {
            return in_array($user->role->name, ['super_admin',  'chief_officer', 'chief_pharmacist']);
        });
        //is Staff
        Gate::define('is_staff', function ($user) {
            return in_array($user->role->name, ['super_admin', 'admin', 'chief_officer', 'chief_pharmacist', 'is_procurement_officer']);
        });
        
        Gate::define('other_staff', function ($user) {
            return in_array($user->role->name, [ 'admin', 'is_procurement_officer', 'med_sup']);
        });

        //permisions
        Gate::define('is_approver', function ($user) {
            return in_array($user->role->name, ['super_admin', 'chief_officer']);
        });

        
        Gate::define('add_tenders', function ($user) {
            return in_array($user->role->name, ['super_admin', 'admin','procurement_officer']);
        });
        Gate::define('approve_applications', function ($user) {
            return in_array($user->role->name, ['super_admin', 'chief_officer']);
        });
        Gate::define('manage_tenders', function ($user) {
            return in_array($user->role->name, ['super_admin', 'admin', 'chief_officer', 'chief_pharmacist','procurement_officer', 'med_sup']);
        });

        //mango production permissions
        Gate::define('add_production', function ($user) {
            return in_array($user->role->name, ['super_admin', 'admin','chief_officer']);
        });

        // mobilizers
        Gate::define('add_production', function ($user) {
            return in_array($user->role->name, ['super_admin', 'admin','chief_officer']);
        });
        Gate::define('add_production', function ($user) {
            return in_array($user->role->name, ['super_admin', 'admin','chief_officer']);
        });
        Gate::define('canViewMobilizers', function ($user) {
            return in_array($user->role->name, ['super_admin', 'Mobilizer','Small Committee' ,'Expanded Committee'.'Kivui for Business Committee']);
        });
        Gate::define('isMobilizer', function ($user) {
            return in_array($user->role->name, ['super_admin', 'Mobilizer','Small Committee' ,'Expanded Committee'.'Kivui for Business Committee']);
        });
        Gate::define('canManageMobilizers', function ($user) {
            return in_array($user->role->name, ['super_admin', 'Mobilizer','Small Committee' ,'Expanded Committee'.'Kivui for Business Committee']);
        });
    }
}
