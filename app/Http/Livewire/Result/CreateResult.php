<?php

namespace App\Http\Livewire\Result;

use Livewire\Component;

use App\Models\User;
use App\Models\Batch;
use App\Models\Result;
use App\Models\ReBoardResult;
use Livewire\WithFileUploads;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\UserLog;

class CreateResult extends Component
{
    use WithFileUploads;

    public $studentName = "";
    public $studentId = 0;
    public $selectStudent = 1;
    public $studentSemester = 1;
    public $editSem = 0;

    public $midStatus = "pass";
    public $midPercentage = "00.00";
    public $midAttachment = NULL;

    public $finalStatus = "pass";
    public $finalPercentage = "00.00";
    public $finalAttachment = NULL;

    public $boardStatus = "pass";
    public $boardPercentage = "00.00";
    public $boardAttachment = NULL;

    public $updateResult = 0;

    public $fetchedResult;

    public $debug = "Degub";

    public $fromBatch = 0;
    public $fromBatchList = [];

    public $backPaperStatus = 0;

    protected function rules()
    {
        return [
            'studentId' => 'required|exists:users,id',
            'studentSemester' => 'required|min:1|max:8',
            'midStatus' => 'required',
            'midPercentage' => 'required|numeric',
            'finalStatus' => 'required',
            'finalPercentage' => 'required|numeric',
            'boardStatus' => 'required',
            'boardPercentage' => 'required|numeric',
            'midAttachment' => 'nullable|mimes:pdf',
            'finalAttachment' => 'nullable|mimes:pdf',
            'boardAttachment' => 'nullable|mimes:pdf',
            'backPaperStatus' => 'nullable|integer'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function enableEdit()
    {
        $this->editSem = 1;
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

                $this->getSetResult();
            }
        }
    }

    public function updatedStudentSemester()
    {
        $this->debug = "New debug";
        $studentExists = User::where('id', $this->studentId)->where('role', 'student')->count();
        if($studentExists == 1)
        {
            // $this->debug = "getset";
            $this->getSetResult();
        }
    }

    public function mount($reqId = 0)
    {        
        if($reqId != 0)
        {
            $studentExists = User::where('id', $reqId)->where('role', 'student')->count();
            if($studentExists == 1)
            {
                $this->studentName =  User::select('name')->where('id', $reqId)->first()->name;
                $this->studentId = $reqId;
                $this->selectStudent = 0;
                $presentSemester = Batch::select('present_sem')
                                    ->where('students.user_id',$reqId)
                                    ->join('students','students.batch_id', '=', 'batches.id')->first();
                $this->studentSemester = $presentSemester->present_sem;

                //getResult and set if present
                $this->getSetResult();
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

    public function getSetResult()
    {
        $this->updateResult = 0;
        $this->resetField();
        $resultExists = Result::all()->where('user_id',$this->studentId)->where('semester',$this->studentSemester)->count();               
        if($resultExists)
        {
            $result = Result::all()->where('user_id',$this->studentId)->where('semester',$this->studentSemester)->first();
            $this->fetchedResult = $result;
            $this->midStatus = $result->mid_status;
            $this->midPercentage = $result->mid_percentage;
            
            $this->finalStatus = $result->final_status;
            $this->finalPercentage = $result->final_percentage;

            $this->boardStatus = $result->board_status;
            $this->boardPercentage = $result->board_percentage;
            $this->updateResult = 1;
        }
    }

    public function store()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $result = new Result;
            $reBoardResult = new ReBoardResult;

            $result->user_id = $this->studentId;
            $result->semester = $this->studentSemester;
            $result->mid_status = $this->midStatus;
            $result->mid_percentage = $this->midPercentage;
            if($this->midAttachment != NULL)
                $result->mid_attachment = $this->midAttachment->store('midResult');        
            
            $result->final_status = $this->finalStatus;
            $result->final_percentage = $this->finalPercentage;
            if($this->finalAttachment != NULL)
                $result->final_attachment = $this->finalAttachment->store('finalResult');
            
            $result->board_status = $this->boardStatus;
            $result->board_percentage = $this->boardPercentage;
                       
            if($this->boardAttachment != NULL){
                $result->board_attachment = $this->boardAttachment->store('boardResult');
                $result->save();
            }
            
            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "New Result Added",
            ]);

            DB::commit();
            if($this->backPaperStatus != 0 )
                session()->flash('success', "Result Added Successfully. The back paper status is neglected as main board attachment has not been added uploaded yet");
            else
                session()->flash('success', "Result Added Successfully");

            $this->resetField();
            return redirect("/student/profile/".$this->studentId);
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Add Result");
            DB::rollback();
        }
    }

    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $oldResult = Result::where('user_id',$this->studentId)->where('semester',$this->studentSemester)->first();

            $oldResult->semester = $this->studentSemester;
            $oldResult->mid_status = $this->midStatus;
            $oldResult->mid_percentage = $this->midPercentage;
            $oldResult->final_status = $this->finalStatus;
            $oldResult->final_percentage = $this->finalPercentage;
            $oldResult->board_status = $this->boardStatus;
            $oldResult->board_percentage = $this->boardPercentage;
            
            
            if($this->midAttachment != NULL)
                $oldResult->mid_attachment = $this->midAttachment->store('midResult');
            if($this->finalAttachment != NULL)
                $oldResult->final_attachment = $this->finalAttachment->store('finalResult');

            if($this->boardAttachment != NULL){
                if($this->backPaperStatus != 1){
                    $oldResult->board_attachment = $this->boardAttachment->store('boardResult');                    
                }else{
                    $newReBoardResult = new ReBoardResult;
                    $newReBoardResult->result_id = $oldResult->id;
                    $newReBoardResult->board_attachment = $this->boardAttachment->store('reBoardResult');    
                    $newReBoardResult->save();                
                }
            }
            
            $oldResult->save();

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Result Updated",
            ]);

            DB::commit();
            session()->flash('success', "Result Successfully Updated");
            return redirect("/student/profile/".$this->studentId); 
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Update The Result");
            DB::rollback();
        }
    }

    public function resetField()
    {
        $this->midStatus = 'pass';
        $this->midPercentage = '00.00';
        $this->midAttachment = NULL;
        $this->finalStatus = 'pass';
        $this->finalPercentage = '00.00';
        $this->finalAttachment = NULL;
        $this->boardStatus = 'pass';
        $this->boardPercentage = '00.00';
        $this->boardAttachment = NULL;
    }

    public function delResultAttachment($type)
    {
        $oldResult = Result::where('user_id',$this->studentId)->where('semester',$this->studentSemester)->first();
        if($type == 1)
        {
            $oldResult->mid_attachment = NULL;
        }elseif($type == 2)
        {
            $oldResult->final_attachment = NULL;
        }elseif($type == 3)
        {
            $oldResult->board_attachment = NULL;
        }
        $oldResult->save();
    }


    public function render()
    {
        $studentList = User::whereHas('student', function($query){
            return $query->where('batch_id','=',$this->fromBatch);
        })->select('id','name')->where('role', 'student')->orderBy('name')->get();
        return view('livewire.result.create-result', ['studentList' => $studentList])->layout('layouts.admin');
    }
}
