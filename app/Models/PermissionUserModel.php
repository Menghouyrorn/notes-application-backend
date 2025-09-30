<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionUserModel extends Model
{
    protected $table = 'permissions_user';
    protected $fillable = ['user_id','permission_id'];

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function permissions()
    {
        return $this->hasMany(PermissionsModel::class, 'id', 'permission_id');
    }
}
