<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'comment',
        'status'
    ];
}
