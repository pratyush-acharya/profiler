<?php

namespace App\Http\Livewire\Batch;

use Livewire\Component;
use App\Models\Batch;
use App\Models\UserLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;



class CreateBatch extends Component
{
    public $year;
    public $stream = "BSc. CSIT";
    public $start_date;
    public $end_date;
    public $grad_date;
    public $present_sem = 1;

    public $update = 0;
    public $reqId = 0;

    protected function rules()
    {
        return [
            'year'=>'required | numeric | between:2010,2100',
            'stream' => [
                'required',
                Rule::unique('batches')->where(function ($query) {
                    $query->where('year', $this->year);
                })->ignore($this->reqId)
            ],
            'start_date'=>'required',
            'end_date'=>'required_if:present_sem,==,9',
            'grad_date'=>'required_if:present_sem,==,9',
            'present_sem'=>'required | numeric | between:1,9'
        ];
    }

    public function mount(Request $request)
    {
        $id = $request->id;
        $stream = $request->stream;

        if($stream == 'bca')
        {
            $this->stream = "BCA";
        }else{
            $this->stream = "BSc. CSIT";
        }

        if($id != 0)
        {
            $this->reqId = $id;
            $this->update = 1;
            $batch = Batch::findOrFail($id);
            $this->year = $batch->year;
            $this->stream = $batch->stream;
            $this->start_date = $batch->start_date;
            $this->end_date = $batch->end_date;
            $this->grad_date = $batch->grad_date;
            $this->present_sem = $batch->present_sem;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetInputFields()
    {
        $this->year = '';
        $this->stream = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->grad_date = '';
        $this->present_sem = '';
    }

    public function store(){
        $this->validate();
        DB::beginTransaction();
        try{
            Batch::create([
                'year' => $this->year,
                'stream' => $this->stream,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'grad_date' => $this->grad_date,
                'present_sem' => $this->present_sem,
                'is_grad' => strval(($this->present_sem > 8)?'1':'0')
            ]);

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "New Batch Created",
            ]);

            DB::commit();
            session()->flash('success', "Batch Created Successfully");

            $this->resetInputFields();
            return redirect('batch/list');
        }catch(\Exception $e){
            session()->flash('danger', "");
            DB::rollback();
        }
    }

    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $batch = Batch::find($this->reqId);
            $batch->update([
                'year' => $this->year,
                'stream' => $this->stream,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'grad_date' => $this->grad_date == "" ? NULL : $this->grad_date,
                'present_sem' => $this->present_sem,
                'is_grad' => strval(($this->present_sem > 8)?'1':'0')
            ]);

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Batch with id => '$this->reqId' Updated ",
            ]);

            DB::commit();
            session()->flash('success', "Batch Successfully Updated");

            return redirect('/batch/list');
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Update Batch");
            DB::rollback();
        }
    }

    public function render()
    {
        return view('livewire.batch.create-batch')->layout('layouts.admin');
    }
}
