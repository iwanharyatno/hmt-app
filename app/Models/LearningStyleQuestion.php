<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningStyleQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array', // simpan array jawaban
    ];

    public function histories()
    {
        return $this->hasMany(LearningStyleHistory::class, 'question_id');
    }
}
