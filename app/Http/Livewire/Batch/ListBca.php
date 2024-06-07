<?php

namespace App\Http\Livewire\Batch;

use Livewire\Component;

use App\Models\Batch;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLog;


class ListBca extends Component
{
    public function delete($batch_id)
    {
        DB::beginTransaction();
        try{
            $batch = Batch::findOrFail($batch_id);
            $studentCount = Student::where('batch_id', $batch->id)->count();
            if($studentCount == 0)
            {
                $batch->delete();
                UserLog::create([
                    'user_id' => Auth::user()->id,
                    'log_detail' => "BCA batch deleted",
                ]);
                DB::commit();
                session()->flash('success', "BCA Batch Deleted Successfully");
            }else{
                session()->flash('warning', "Batch Contains Student");
            }
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Delete Batch");
            DB::rollback();
        }
    }
    
    public function render()
    {
        $result = Batch::select('id', 'year')->where('stream', 'BCA')->where('is_grad', '0')->orderBy('year', 'desc')->get();
        return view('livewire.batch.list-bca', ['batches' => $result]);
    }
}
