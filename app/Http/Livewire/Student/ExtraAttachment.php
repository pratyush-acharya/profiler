<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;
use App\Models\Attachment;
use App\Models\User;


class ExtraAttachment extends Component
{
    public $attachmentList;
    
    public function mount($userId)
    {
        // $this->attachmentList = User::find($userId)->role;
        if(User::find($userId)->role == 'student')
        {
            $this->attachmentList = Attachment::with(array('attachmentCategory'=>function($query){
                $query->select('id','category_name');
            }))->select('id', 'user_id', 'attachment_category_id', 'url')->where('user_id', $userId)->get();
        }
    }

    public function downloadAttachment($reqId)
    {
        $fileAray = Attachment::select('url')->where('id', $reqId)->first();
        if(\Storage::exists($fileAray->url))
            return \Storage::download($fileAray->url);
        else
            session()->flash('success', 'File Not Found');
    }

    public function deleteAttachment($reqId)
    {
        $attachment = Attachment::where('id', $reqId)->first();
        if(\Storage::exists($attachment->url))
            \Storage::delete($attachment->url);
        $attachment->delete();
        session()->flash('success', 'Attachment Deleted Successfully');
        return redirect('/student/attachment/'.$attachment->user_id);
    }

    public function render()
    {
        return view('livewire.student.extra-attachment')->layout('layouts.admin');
    }
}
