<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgress extends Model
{
    use HasFactory;

    protected $table = 'training_progress';

    protected $fillable = [
        'user_id',
        'module_id',
        'completed',
        'score',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }
}