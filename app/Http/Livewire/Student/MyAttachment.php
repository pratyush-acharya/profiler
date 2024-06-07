<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class MyAttachment extends Component
{

    public $attachmentList;
    
    public function mount()
    {
        $userId = Auth::user()->id;
        if(User::find($userId)->role == 'student')
        {
            $this->attachmentList = Attachment::with(array('attachmentCategory'=>function($query){
                $query->select('id','category_name');
            }))->select('id', 'user_id', 'attachment_category_id', 'url')->where('user_id', $userId)->get();
        }
    }

    public function downloadAttachment($reqId)
    {
        $fileAray = Attachment::select('url')->where('user_id', Auth::user()->id)->where('id', $reqId)->first();
        if(\Storage::exists($fileAray->url))
            return \Storage::download($fileAray->url);
        else
            return session()->flash('warning', 'File Not Found');
    }

    public function render()
    {
        return view('livewire.student.my-attachment')->layout('layouts.student');
    }    
}
