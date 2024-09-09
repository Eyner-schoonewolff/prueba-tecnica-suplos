<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes; 
    protected $table = 'tasks';
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'description', 'user_id','completed'];

    // Relaciones
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
