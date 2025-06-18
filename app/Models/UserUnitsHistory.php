<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUnitsHistory extends Model
{
    protected $table = 'user_units_history';
    protected $primaryKey = 'id_user_history';
    public $timestamps = false; 

    protected $fillable = [
        'id_users',
        'id_learning_units',
        'status',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function learningUnit()
    {
        return $this->belongsTo(LearningUnit::class, 'id_learning_units', 'id_learning_units');
    }
}
