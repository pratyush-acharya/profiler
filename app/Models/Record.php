<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'semester',
        'category_id',
        'comment',
        'start_date',
        'end_date',
        'refer_to'
    ];

    public function attachments()
    {
        return $this->hasMany(RecordAttachment::class, 'record_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
