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
use App\Models\UserLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CreateRecord extends Component
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

    public $fromBatch = 0;
    public $fromBatchList = [];

    public $attachments = [];

    protected function rules()
    {
        return [
            'comment' => 'required',
            'category' => 'required|exists:categories,id',
            'studentId' => 'required|exists:users,id',
            'studentSemester' => 'required|numeric|min:1|max:8'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($reqId = 0)
    {        
        $categoryCount = Category::select('id', 'is_date', 'is_end_date')->where('status','1')->count();
        if($categoryCount > 0)
        {
            $category = Category::select('id', 'is_date', 'is_end_date')->where('status','1')->orderBy('created_at', 'desc')->first();
            $this->category = $category->id;
            $this->isStartDate = $category->is_date;       
            $this->isEndDate = $category->is_end_date;
        }
        

        if($reqId != 0)
        {
            $studentExists = User::where('id', $reqId)->where('role', 'student')->count();
            if($studentExists == 1)
            {
                $this->studentName =  User::select('name')->where('id', $reqId)->first()->name;
                $this->studentId = $reqId;
                $this->selectStudent = 0;
                $presentSemster = Batch::select('present_sem')
                                    ->where('students.user_id',$reqId)
                                    ->join('students','students.batch_id', '=', 'batches.id')->first();
                $this->studentSemester = $presentSemster->present_sem;
            }
        }else{
            try{
                $this->fromBatch = Batch::first()->id;
                $this->fromBatchList = Batch::select('id','year','stream')->orderBy('year', 'desc')->orderBy('stream')->get();
            }catch(\Exception $e)
            {
                return redirect('/batch/create');
            }
        }
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
        $this->validate();
        DB::beginTransaction();
        try{
            $record = new Record();
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
                'log_detail' => "New Record Created",
            ]);

            DB::commit();
            session()->flash('success', 'New Record Created Successfully');
        
            return redirect('/student/profile/'.$this->studentId);
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Create The Record");
            DB::rollback();
        }
    }

    public function render()
    {
        $categories = Category::select('id','name')->where('status','1')->orderBy('name')->get();
        $comments = CategoryComment::select('comment')->where('category_id',$this->category)->where('status','1')->orderBy('comment')->get();
        $studentList = User::whereHas('student', function($query){
            return $query->where('batch_id','=',$this->fromBatch);
        })->select('id','name')->where('role', 'student')->orderBy('name')->get();
        return view('livewire.record.create-record', ['categories' => $categories, 'comments' => $comments, 'studentList' => $studentList])->layout('layouts.admin');
    }
}
