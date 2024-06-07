<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AttachmentCategory;


class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attachment_category_id',
        'url'
    ];

    public function attachmentCategory()
    {
        return $this->belongsTo(AttachmentCategory::class, 'attachment_category_id');
    }
}
