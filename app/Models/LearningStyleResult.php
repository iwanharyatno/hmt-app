<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningStyleResult extends Model
{
    protected $fillable = [
        'user_id',
        'result',
        'details'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
