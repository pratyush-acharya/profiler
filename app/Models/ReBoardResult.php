<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReBoardResult extends Model
{
    use HasFactory;

    
    protected $fillable=[
        'result_id',
        'board_attachment'
    ];

    public function result(){
        return $this->belongsTo(Result::class);
    }
}
