<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'semester',
        'mid_status',
        'mid_percentage',
        'mid_attachment',
        'final_status',
        'final_percentage',
        'final_attachment',
        'board_status',
        'board_percentage',
        'board_attachment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reBoardResult(){
        return $this->hasMany(ReBoardResult::class,'result_id','id');
    }
}
