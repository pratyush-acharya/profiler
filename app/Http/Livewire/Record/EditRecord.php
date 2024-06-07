<?php

namespace App\Http\Livewire\Record;

use Livewire\Component;

use App\Models\Record;
use App\Models\User;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Category;
use App\Models\CategoryComment;
use App\Models\RecordAttachment;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\UserLog;

class EditRecord extends Component
{
    use WithFileUploads;

    public $isModify = 0;
    public $comment = 0;
    public $category;
    public $isStartDate = 0;
    public $isEndDate = 0;
    public $studentId = 0;
    public $selectStudent = 1;
    public $startDate = NULL;
    public $endDate = NULL;

    public $studentSemester = 1;
    public $editSem = 0;
    public $studentName = "";

    public $attachments = [];

    public $oldRecordId;

    public function mount($reqId)
    {   
        $this->oldRecordId = $reqId;  
        $oldRecord = Record::select('id', 'user_id', 'semester', 'category_id', 'comment', 'start_date', 'end_date')->where('id', $reqId)->first();
        
        $this->category = $oldRecord->category_id;
        if($oldRecord->end_date != NULL){
            $this->isStartDate = 1;
            $this->isEndDate  = 1;
            $this->startDate = $oldRecord->start_date;
            $this->endDate = $oldRecord->end_date;
        }elseif($oldRecord->end_date == NULL && $oldRecord->start_date != NULL)
        {
            $this->isStartDate = 1;
            $this->isEndDate  = 0;
            $this->startDate = $oldRecord->start_date;
        }else{
            $this->isStartDate = 0;
            $this->isEndDate  = 0;
        }        

        $this->studentName =  User::select('name')->where('id', $oldRecord->user_id)->first()->name;
        $this->studentId = $oldRecord->user_id;
        $this->selectStudent = 0;
        $this->studentSemester = $oldRecord->semester;
        $this->modify();
        $this->comment = $oldRecord->comment;
    }

    public function modify()
    {
        $this->isModify = 1;
    }

    public function enableEdit()
    {
        $this->editSem = 1;
    }

    public function custom()
    {
        $this->isModify = 1;
        $this->comment = '';
    }

    public function listComment()
    {
        $this->isModify = 0;
        $this->comment = 0;
    }

    public function remove($field)
    {
        if($field == 1)
        {
            $this->isStartDate = 0;
            $this->isEndDate = 0;
            $this->startDate = NULL;
            $this->endDate = NULL;
        }elseif($field = 2)
        {
            $this->isEndDate = 0;
            $this->endDate = NULL;
        }
    }

    public function add($field)
    {
        if($field == 1)
        {
            $this->isStartDate = 1;
        }elseif($field == 2)
        {
            $this->isEndDate = 1;
        }elseif($field == 3)
        {
            //
        }
    }

    public function default()
    {
        $this->isModify = 0;
    }

    public function updatedStudentId()
    {
        if($this->studentId != 0)
        {
            $studentExists = User::where('id', $this->studentId)->where('role', 'student')->count();
            if($studentExists == 1)
            {
                $presentSemster = Batch::select('present_sem')
                                    ->where('students.user_id',$this->studentId)
                                    ->join('students','students.batch_id', '=', 'batches.id')->first();
                $this->studentSemester = $presentSemster->present_sem;
            }
        }
    }

    public function updatedCategory()
    {
        $categories = Category::select('is_date','is_end_date')->where('id', $this->category)->first();
        $this->isStartDate = $categories->is_date;       
        $this->isEndDate = $categories->is_end_date;       
    }    

    public function store()
    {
        DB::beginTransaction();
        try{
            $record = Record::find($this->oldRecordId);
            $record->user_id = $this->studentId;
            $record->semester = $this->studentSemester;
            $record->category_id = $this->category;
            $record->comment = $this->comment;
            $record->start_date = $this->startDate;
            $record->end_date = $this->endDate;
            $record->save();

            foreach ($this->attachments as $attachment) {
                RecordAttachment::create([
                    'user_id' => $this->studentId,
                    'category_id' => $this->category,
                    'record_id' => $record->id,
                    'url' => $attachment->store('recordAttachment')
                ]);
            }

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Record Edited",
            ]);
                
            DB::commit();
            session()->flash('success', 'Record Edited Successfully');

            return redirect('/student/profile/'.$this->studentId);
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Update The Record");
            DB::rollback();
        }
    }

    public function deleteAttachment($attachmentId)
    {
        DB::beginTransaction();
        try{
            $attachment = RecordAttachment::find($attachmentId);
            if(Storage::exists($attachment->url))
                Storage::delete($attachment->url);
            $attachment->delete();
    
            DB::commit();
            session()->flash('success', "Record Deleted Successfully");
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Delete Data Completely");
            DB::rollback();
        }
    }

    public function downloadRecordAttachment($reqId)
    {
        $fileAray = RecordAttachment::select('url')->where('id', $reqId)->first();
        if(Storage::exists($fileAray->url))
            return \Storage::download($fileAray->url);
        else
            session()->flash('warning', "File Not Found");
    }

    public function render()
    {
        $categories = Category::select('id','name')->where('status','1')->get();
        $comments = CategoryComment::select('comment')->where('category_id',$this->category)->get();
        $studentList = User::select('id','name')->where('role', 'student')->get();
        $oldAttachments = RecordAttachment::select('id', 'record_id', 'url')->where('record_id', $this->oldRecordId)->get();
        return view('livewire.record.edit-record', ['categories' => $categories, 'comments' => $comments, 'studentList' => $studentList, 'oldAttachments' => $oldAttachments])->layout('layouts.admin');
    }
}
