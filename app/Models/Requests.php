<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
