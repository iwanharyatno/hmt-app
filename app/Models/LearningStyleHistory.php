<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningStyleHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'answer_index',
    ];

    public function question()
    {
        return $this->belongsTo(LearningStyleQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
