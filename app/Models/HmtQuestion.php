<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HmtQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_path',
        'answer_paths',
        'correct_index',
        'is_example',
        'solution_description'
    ];

    protected $casts = [
        'answer_paths' => 'array', // otomatis cast json ke array
    ];

    public function histories()
    {
        return $this->hasMany(HmtHistory::class, 'question_id');
    }
}
