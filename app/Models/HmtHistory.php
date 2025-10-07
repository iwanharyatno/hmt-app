<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HmtHistory extends Model
{
    use HasFactory;

    public $timestamps = false; // 👈 disable created_at & updated_at

    protected $fillable = [
        'question_id',
        'user_id',
        'answer_index',
        'answered_at',
        'attempts',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function question()
    {
        return $this->belongsTo(HmtQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
