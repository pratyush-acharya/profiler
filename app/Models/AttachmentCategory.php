<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use App\Models\Attachment;

class AttachmentCategory extends Model
{
    use HasFactory;

    public $fillable = ['category_name'];

    // public function attachmentCategory()
    // {
    //     return $this->hasMany(Attachment::class);
    // }
    
}
