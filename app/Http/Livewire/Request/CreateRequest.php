<?php

namespace App\Http\Livewire\Request;

use Livewire\Component;
use App\Models\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\UserLog;
use App\Models\Request;


class CreateRequest extends Component
{
    public $message;

    protected function rules()
    {
        return [
            'message' => 'required|string|max:255'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetInputFields()
    {
        $this->message = '';
        // $this->record_id = '';
    }

    public function store()
    {
        $this->validate();
        
        DB::beginTransaction();
        try{
            Requests::create([
                'user_id' => Auth::user()->id,
                'message' => $this->message
            ]);

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "New Request Made",
            ]);

            DB::commit();
            session()->flash('success', "Request Made Successfully");          
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Make Request");
            DB::rollback();
        }
    }

    public function render()
    {
        $result = Requests::select('id', 'message', 'state', 'created_at', 'updated_at')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->get();
        return view('livewire.request.create-request', ['requests' => $result])->layout('layouts.student');
    }
}
