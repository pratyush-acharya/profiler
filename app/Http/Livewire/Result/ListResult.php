<?php

namespace App\Http\Livewire\Result;

use Livewire\Component;

use App\Models\Result;

class ListResult extends Component
{
    public $userId;
    public $semester;

    public function mount()
    {
        
    }


    public function render()
    {
        $result = Result::where('user_id', $this->userId)
        ->where('semester', $this->semester)
        ->first();

        return view('livewire.result.list-result', ['result' => $result]);
    }
}
