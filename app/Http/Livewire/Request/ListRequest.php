<?php

namespace App\Http\Livewire\Request;

use Livewire\Component;
use App\Models\Requests;

class ListRequest extends Component
{
    public $state;

    public function render()
    {
        $result = Requests::with('user:id,name')->select('requests.id', 'requests.user_id', 'requests.message', 'batches.year', 'batches.stream', 'students.image')
        ->join('students', 'requests.user_id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('state', 'ongoing')->get();

        return view('livewire.request.list-request', ['requests' => $result])->layout('layouts.admin');
    }
}
