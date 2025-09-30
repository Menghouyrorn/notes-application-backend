<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionsModel extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name','id'];

    public function users()
    {
        return $this->belongsToMany(User::class,'permissions','permission_id','user_id');
    }
}
