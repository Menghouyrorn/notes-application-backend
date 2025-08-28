<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
