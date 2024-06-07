<?php

namespace App\Http\Livewire\Request;

use Livewire\Component;
use Illuminate\Support\Facades\DB;


use App\Models\Requests;

class RequestState extends Component
{
    public $requestState;
    public $requestId;

    public function updatedRequestState()
    {
        DB::beginTransaction();
        try{
            $request = Requests::find($this->requestId);
            $request->update([
                'state' => $this->requestState
            ]);

            DB::commit();
            session()->flash('success', "Request State Changed Successfully");
        }catch(\Exception $e){
            session()->flash('danger', "Failed Changing Request State");
            DB::rollback();
        }
    }

    public function render()
    {
        $result = Requests::select('state')->where('id', $this->requestId)->first(); 
        $this->requestState = $result->state;
        return view('livewire.request.request-state');
    }
}
