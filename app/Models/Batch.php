<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'stream',
        'start_date',
        'end_date',
        'grad_date',
        'present_sem',
        'is_grad'
    ];
}
