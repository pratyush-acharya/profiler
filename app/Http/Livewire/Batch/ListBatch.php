<?php

namespace App\Http\Livewire\Batch;

use Livewire\Component;

use App\Models\Batch;
use App\Models\Student;
use Illuminate\Support\Facades\DB;


class ListBatch extends Component
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
                DB::commit();
                session()->flash('success', "Batch Has Been Successfully Deleted!");
            }else{
                session()->flash('warning', "Batch Contains Students");
            }
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Delete Batch");
            DB::rollback();
        }
    }

    public function addOne($batch_id)
    {
        DB::beginTransaction();
        try{
            $batch = Batch::findOrFail($batch_id);
            $present_sem = $batch->present_sem;
            if($present_sem < 8)
            {
                $batch->update([
                    'present_sem' => $present_sem + 1
                ]);
                
                DB::commit();
                session()->flash('success', 'Semester Added');
            }elseif($present_sem == 8)
            {
                $batch->update([
                    'present_sem' => $present_sem + 1,
                    'is_grad' => strval('1')
                ]);

                DB::commit();
                session()->flash('success', 'Please Add Graduation Date');
                return redirect("batch/edit/".$batch_id);
            }
        }catch(\Exception $e){
            session()->flash('danger', "Failed Adding Semester");
            DB::rollback();
        }
    }

    public function render()
    {
        $result = Batch::select('id', 'year', 'stream', 'start_date', 'end_date', 'grad_date', 'present_sem', 'is_grad')->orderBy('is_grad', 'desc')->orderBy('stream', 'asc')->orderBy('year', 'desc')->get();
        return view('livewire.batch.list-batch', ['batches' => $result])->layout('layouts.admin');
    }
}
