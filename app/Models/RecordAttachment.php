<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordAttachment extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'category_id',
        'url',
        'record_id'
    ];
}
