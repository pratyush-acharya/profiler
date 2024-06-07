<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;

use App\Models\User;
use App\Models\Result;
use App\Models\ReBoardResult;


class BoardResultAttachment extends Component
{
    public $boardAttachments;
    public $reBoardAttachments;
    
    public function mount($userId)
    {
        if(User::find($userId)->role == 'student')
        {
            $this->boardAttachments = Result::where('user_id',$userId)->with('reBoardResult')->get();           
        }
    }

    public function deleteReBoardAttachment($reBoardId)
    {
        $reBoardAttachment = ReBoardResult::where('id', $reBoardId)->first();
        if(\Storage::exists($reBoardAttachment->board_attachment))
            \Storage::delete($reBoardAttachment->board_attachment);
        $reBoardAttachment->delete();

        session()->flash('success', 'Re-Exam Result Attachment Deleted Successfully');
        return redirect('/student/boardResult/'.$reBoardAttachment->result->user_id);
    }

    public function render()
    {
        return view('livewire.student.board-result-attachment')->layout('layouts.admin');
    }
}
