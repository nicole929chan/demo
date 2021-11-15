<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 檢核是否具備某一種角色
     * @param mixed $roles 
     * @return bool 
     */
    public function hasRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contain('name', $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 從角色與個人, 檢核User是否具備某一項權限;
     * @param Permission $permission 
     * @return bool 
     */
    public function hasPermissionTo(Permission $permission): bool
    {
        return $this->hasPermission($permission) || $this->hasPermissionThroughRole($permission);
    }

    /**
     * 授予權限, 一次可授予多項;
     * @param mixed $permissions 
     * @return $this 
     */
    public function givePermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));

        if (is_null($permissions)) {
            return $this;
        }

        $this->permissions()->saveMany($permissions);

        return $this;
    }

    /**
     * 撤銷權限, 一次可撤銷多項;
     * @param mixed $permissions 
     * @return $this 
     * @throws InvalidArgumentException 
     */
    public function withdrawPermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));

        $this->permissions()->detach($permissions);

        return $this;
    }

    /**
     * 更新權限
     * @param mixed $permission 
     * @return $this 
     * @throws InvalidArgumentException 
     */
    public function updatePermissions(...$permission)
    {
        $this->permissions()->detach();

        return $this->givePermissionTo($permission);
    }

    /**
     * 取得所查詢的權限集合
     * @param array $permissions 
     * @return mixed 
     */
    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('name', $permissions)->get();
    }

    /**
     * 檢核User歸屬的角色是否有該項權限
     * @param Permission $permission 
     * @return bool 
     */
    protected function hasPermissionThroughRole(Permission $permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 檢核User是否有該項權限
     * @param Permission $permission 
     * @return bool 
     */
    protected function hasPermission(Permission $permission): bool
    {
        return (bool)$this->permissions->where('name', $permission->name)->count();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions')
            ->withTimestamps();
    }
}
