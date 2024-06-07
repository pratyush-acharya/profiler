<?php

namespace App\Http\Livewire\Attachment;

use Livewire\Component;

use App\Models\AttachmentCategory;
use App\Models\UserLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AttachmentCategoory extends Component
{
    public $cat_id = 0;
    public $state = 1;  // 1=>add, 2=>update
    public $categoryName;

    public function rules()
    {
        return [
            'categoryName' => 'required'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function create()
    {   
        $this->validate();

        DB::beginTransaction();
        try{
            AttachmentCategory::create([
                'category_name' => $this->categoryName,
            ]);

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "New Attachment Category Created",
            ]);

            DB::commit();
            session()->flash('success', "Attachment Category Added Successfully");

            $this->reset(['categoryName']);
            $this->emitUp('updateAttachmentCategory');
        }catch(\Exception $e){
            session()->flash('danger', "Failed Creating Attachment Category");
            DB::rollback();
        }
    }

    public function editState($catId)
    {
        $this->state = 2;
        $attachmentCategory = AttachmentCategory::where('id', $catId)->first();
        $this->categoryName = $attachmentCategory->category_name;
        $this->cat_id = $catId;
    }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try{
            $category = AttachmentCategory::find($this->cat_id);
            $category->update([
                'category_name' => $this->categoryName
            ]);
            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Attachment Category Updated",
            ]);

            DB::commit();
            session()->flash('success', "Attachment Category Updated Successfully");

            $this->emitUp('updateAttachmentCategory');
            $this->state = 1;
            $this->reset(['categoryName', 'cat_id']);
        }catch(\Exception $e){
            session()->flash('danger', "Failed Updating Attachment Category");
            DB::rollback();
        }        
    }
    
    public function render()
    {
        $result = AttachmentCategory::select('id', 'category_name')->get();
        return view('livewire.attachment.attachment-categoory', ['attachmentCategories' => $result])->layout('layouts.admin');
    }
}
