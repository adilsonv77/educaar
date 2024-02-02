<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('student', function($user){
            return $user->type == 'student';
        });

        $gate->define('teacher', function($user){
            return $user->type == 'teacher';
        });

        $gate->define('admin', function($user){
            return $user->type == 'admin';
        });
        $gate->define('developer', function($user){
            return $user->type == 'developer';
        });
    }
}
