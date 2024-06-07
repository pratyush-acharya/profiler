<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;

use App\Models\User;
use App\Models\Student;
use App\Models\Batch;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;


class ListStudent extends Component
{
    public $stream;
    public $year;
    public $semester;
    public $batchId = 1;
    public $debug = "";
    public $search = "";
    public $studentCount = 0;

   public $reqStream = "BSc. CSIT";
   public $reqYear = 1;

    public $batchList;

    public $students;

    public function mount()
    {
        // $this->debug .= "mount <br>";
        $this->initPage();
    }

    public function initPage()
    {
        $batchCount = Batch::where('stream', $this->reqStream)->count();

        if($batchCount == 0 && $this->reqStream == "BCA")
        {
            $this->reqStream = "BSc. CSIT";
        }else if($batchCount == 0 && $this->reqStream == "BSc. CSIT"){
            $this->reqStream = "BCA";
        }

        $batchCount = Batch::where('stream', $this->reqStream)->count();
        if($batchCount > 0)
        {
            $this->stream = $this->reqStream;
            $this->batchList = Batch::select('id', 'year', 'present_sem')->where('stream', $this->stream)->get();
            $this->year = $this->batchList->first()->year;
            $this->reqYear = $this->batchList->first()->year;
            $this->semester = $this->batchList->first()->present_sem;
        }else{
            return redirect('/batch/create/');
        }
    }

    public function updatedReqStream()
    {
        // $this->debug .= "updateReqStream <br>". $this->students;
        $this->batchList = Batch::select('id', 'year')->where('stream', $this->reqStream)->get();
        if($this->batchList->count() > 0)
            $this->reqYear = $this->batchList->first()->year;
    }

    public function show()
    {
        // $this->debug .= "show <br>";
        $this->search = "";
        $this->year = $this->reqYear;
        $this->stream = $this->reqStream;
        $this->semester = Batch::select('present_sem')->where('stream', $this->stream)->where('year', $this->year)->first();
        if(!empty($this->semester))
           $this->semester = $this->semester->present_sem;
        // $this->semester = Batch::select('present_sem')->where('stream', $this->stream)->where('year', $this->year)->first()->present_sem;
    }

    public function getStudents()
    {
        // $this->debug .= "getstudent <br>";
        $this->students = User::select('users.id', 'users.name', 'users.email', 'users.status', 'students.roll', 'students.batch_id', 'batches.year', 'batches.stream')
        ->join('students','users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('year', $this->year)
        ->where('stream', $this->stream)
        ->orderBy('students.roll')
        ->get();

        $this->studentCount = $this->students->count();
    }

    public function searchStudent()
    {
        $this->students = User::select('users.id', 'users.name', 'users.email', 'students.roll', 'students.batch_id', 'batches.year', 'batches.stream', 'users.status')
        ->join('students','users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('users.email', 'LIKE', "%$this->search%")
        ->orWhere('users.name', 'LIKE', "%$this->search%")
        ->limit(20)
        ->get();
    }

    public function changeStatus($userId)
    {
        DB::beginTransaction();
        try{
            $user = User::where('id', $userId)->first();
            $user->status = strval(1 - $user->status);
            $user->save();

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => 'Status Changed',
            ]);
            DB::commit();
            session()->flash('success', "User Status Successfully Updated");
        }catch(\Exception $e){
            session()->flash('danger', "Failed Updating User Status");
            DB::rollback();
        }
    }

    public function render()
    {
        if($this->search == "")
        {
            $this->getStudents();
        }else{
            $this->searchStudent();
        }

        // $this->debug .= "render <br>";
        return view('livewire.student.list-student')->layout('layouts.admin');
    } 
}
