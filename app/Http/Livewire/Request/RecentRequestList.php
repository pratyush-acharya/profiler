<?php

namespace App\Http\Livewire\Request;

use Livewire\Component;

use App\Models\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\UserLog;


class RecentRequestList extends Component
{
    protected $states = ['requested', 'ongoing', 'solved', 'rejected'];
    
    public function changeState($requestId, $changeTo)
    {
        if(in_array($changeTo, $this->states))
        {
            DB::beginTransaction();
            try{
                $request = Requests::find($requestId);
                $request->update([
                    'state' => $changeTo
                ]);
                UserLog::create([
                    'user_id' => Auth::user()->id,
                    'log_detail' => "Request Updated",
                ]);

                DB::commit();
                session()->flash('success', "Request Updated Successfully");
            }catch(\Exception $e){
                session()->flash('danger', "Request Failed To Update");
                DB::rollback();
            }
        }
    }

    public function render()
    {
        $result = Requests::with('user:id,name')->select('requests.id', 'requests.user_id', 'requests.message', 'batches.year', 'batches.stream', 'students.image')
        ->join('students', 'requests.user_id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('state', 'requested')
        ->orderBy('requests.created_at', 'desc')
        ->get();
        return view('livewire.request.recent-request-list', ['requests' => $result]);
    }
}
