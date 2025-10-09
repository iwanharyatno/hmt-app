<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HmtHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'question_id',
        'answer_index',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(HmtSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(HmtQuestion::class, 'question_id');
    }
}
