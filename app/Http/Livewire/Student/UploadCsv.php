<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;
use App\Models\Batch;

use Livewire\WithFileUploads;

class UploadCsv extends Component
{
    use WithFileUploads;

    public $batch;
    public $attachment;

    protected $rules = [
        'attachment'=>'required|mimes:csv,txt|max:10000',
    ];

    public function render()
    {
        $batchList = Batch::select('id', 'year', 'stream')->get();
        return view('livewire.student.upload-csv', ['batchList' => $batchList]);
    }

    public function upload(){
        $this->validate();

    }
}
