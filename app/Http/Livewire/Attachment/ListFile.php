<?php

namespace App\Http\Livewire\Attachment;

use App\Models\Attachment;
use Livewire\Component;

class ListFile extends Component
{

    public function export($url)
    {
        return Storage::disk('attachment')->download($url);
    }

    public function render()
    {
        $result = Attachment::all();
        return view('livewire.attachment.list-file', ['files' => $result]);
    }
}
