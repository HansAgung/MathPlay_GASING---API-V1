<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModuleHistory extends Model
{
    protected $table = 'user_module_history';
    protected $primaryKey = 'id_module_history';
    public $timestamps = false;

    protected $fillable = [
        'id_users',
        'id_learning_modules',
        'status',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function learningModule(): BelongsTo
    {
        return $this->belongsTo(LearningModule::class, 'id_learning_modules', 'id_learning_modules');
    }
}
