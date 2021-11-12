<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * 定義(檢核)User的權限 - 從個人權限表與角色 
     *
     * @return void
     */
    public function boot()
    {
        $permissions = Permission::get();

        $permissions->map(function ($permission) {
            Gate::define($permission->name, function (User $user) use ($permission) {
                // 判斷權限的邏輯
                return $user->hasPermissionTo($permission);
            });
        });
    }
}
