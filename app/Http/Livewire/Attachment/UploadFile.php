<?php

namespace App\Http\Livewire\Attachment;

use Livewire\Component;

use App\Models\Attachment;
use App\Models\User;
use App\Models\Batch;
use App\Models\AttachmentCategory;

use Livewire\WithFileUploads;
use File;
use Illuminate\Support\Facades\DB;

class UploadFile extends Component
{
    use WithFileUploads;

    protected $listeners = ['updateAttachmentCategory' => 'getAttachmentCategory'];

    public $update = 0;
    public $user_id;
    public $type = 0;
    public $attachment;

    public $reqId = 0;
    public $categoryList;

    public $fromBatch = 0;
    public $fromBatchList = [];

    protected function rules()
    {
        return [
            'attachment' => 'required|mimes:pdf|max:10000',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|exists:attachment_categories,id'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($id = 0)
    {
        if($id != 0)
        {
            $this->reqId = $id;
            $this->update = 1;
            $file = Attachment::findOrFail($id);
            $this->user_id = $file->user_id;
            $this->type = $file->attachment_category_id;
        }else{
            $this->fromBatch = Batch::first()->id;
            $this->fromBatchList = Batch::select('id','year','stream')->orderBy('year', 'desc')->orderBy('stream')->get();
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try{
            $filePath = $this->attachment->store('attachment');
            Attachment::create([
                'user_id' => $this->user_id,
                'attachment_category_id' => $this->type,
                'url' => $filePath
            ]);

            DB::commit();
            session()->flash('success', "Attachment Uploaded Successfully");
            // $this->reset(['user_id','type', 'attachment']);
        }catch(\Exception $e){
            session()->flash('danger', "Failed Uploading Attachment");
            DB::rollback();
        }

        $this->attachment = [];

        // dd($this->user_id);

        return redirect('/student/attachment/'.$this->user_id);
    }

    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $attachment = Attachment::find($this->reqId);

            $this->validate([
                'attachment' => 'required|mimes:pdf|max:10000'
            ]);
            
            $filePath = $this->attachment->store('attachment');
    
            $attachment->update([
                'url' => $filePath  
            ]);

            DB::commit();
            session()->flash('success', "Attachment Updated Successfully");

            return redirect('/student/attachment/'.$this->user_id);
        }catch(\Exception $e){
            session()->flash('danger', "Failed Updating The Attachment");
            DB::rollback();
        }
    }

    public function getAttachmentCategory()
    {
        $this->categoryList = AttachmentCategory::select('id', 'category_name')->get();
    }


    public function render()
    {
        $this->getAttachmentCategory();
        $studentList = User::whereHas('student', function($query){
            return $query->where('batch_id','=',$this->fromBatch);
        })->select('id','name')->where('role', 'student')->orderBy('name')->get();
        return view('livewire.attachment.upload-file', ['studentList' => $studentList])->layout('layouts.admin');
    }
}
