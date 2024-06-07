<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecordAttachment;

class RecordAttachmentController extends Controller
{
    public function viewFile($attachmentId)
    {
        $data = RecordAttachment::select('url')->where('id', $attachmentId)->first();

        // return response()->file($data->url, ["Content-type:application/pdf"]);
        //echo response()->file($data->url);
    }
}
