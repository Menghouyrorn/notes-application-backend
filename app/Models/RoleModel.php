<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name','id'];

    public function users()
    {
        return $this->hasMany(User::class,'role_id','id');
    }
}
