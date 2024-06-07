<?php

namespace App\Http\Livewire\Record;

use Livewire\Component;

use App\Models\User;
use App\Models\Record;
use App\Models\RecordAttachment;
use Illuminate\Support\Facades\Storage;

class ListRecord extends Component
{
    public $userId = 0;
    public $reqSem = 1;
    public $recordList;

    public function mount($userId, $reqSem = 1)
    {
        $userExists = User::where('id', $userId)->where('role', 'student')->count();

        if($userExists == 1)
        {
            $this->userId = $userId;
            $this->reqSem = $reqSem;
            $this->getRecord();
        }      
    }

    public function getRecord()
    {
        $this->recordList = Record::select('id', 'comment', 'start_date', 'end_date', 'category_id')
        ->where('semester', $this->reqSem)
        ->where('user_id', $this->userId)
        ->get();
    }

    public function deleteRecord($recordId)
    {
        DB::beginTransaction();
        try{
            $record = Record::find($recordId);
            $attachments = RecordAttachment::where('record_id', $record->id)->get();
            foreach($attachments as $attachment)
            {
                if(Storage::exists($attachment->url))
                    Storage::delete($attachment->url);
                $attachment->delete();
            }
            $record->delete();
            DB::commit();
            session()->flash('success', "Records Successfully Deleted");
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Delete Record");
            DB::rollback();
        }
    }

    public function render()
    {
        $this->getRecord();
        return view('livewire.record.list-record');
    }
}
