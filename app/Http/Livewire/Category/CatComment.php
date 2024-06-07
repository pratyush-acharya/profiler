<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;

use App\Models\CategoryComment;
use App\Models\Category;
use App\Models\UserLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class CatComment extends Component
{
    public $cat_id;
    public $comment;
    public $state = 1;  // 1=>add, 2=>update
    public $category;
    public $comment_id;

    protected $listeners = ['loadCustomComment' => 'mount'];

    public $debug;

    protected function rules()
    {
        return [
            'cat_id' => 'required|exists:categories,id',
            'comment' => 'required'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($id)
    {
        $this->debug = $id;

        $this->cat_id = $id;
        $this->category = Category::select('name')->findOrFail($id)->name;
    }

    public function create()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            CategoryComment::create([
                'category_id' => $this->cat_id,
                'comment' => $this->comment
            ]);

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Category Comment Created",
            ]);
            
            DB::commit();
            session()->flash('success', "Category Comment Successfully Created");

            $this->reset(['comment']);
        }catch(\Exception $e){
            session()->flash('danger', "Failed Creating Category Comment");
            DB::rollback();
        }
    }

    public function editState($commentId)
    {
        $this->state = 2;
        $comment = CategoryComment::where('id', $commentId)->first();
        $this->comment = $comment->comment;
        $this->comment_id = $commentId;
    }

    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $category = CategoryComment::find($this->comment_id);
            $category->update([
                'comment' => $this->comment
            ]);
            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Category Comment Updated",
            ]);
            
            DB::commit();
            session()->flash('success', "Category Comment Successfully Updated");

            $this->state = 1;
            $this->reset(['comment']);
        }catch(\Exception $e){
            session()->flash('danger', "Failed Updating Category Comment");
            DB::rollback();
        }
    }

    public function delete($comment_id)
    {
        DB::beginTransaction();
        try{
            $comment = CategoryComment::findOrFail($comment_id);
            $comment->delete();
            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Category Comment Deleted",
            ]);

            DB::commit();
            session()->flash('success', "Category Comment Successfully Deleted");
        }catch(\Exception $e){
            session()->flash('danger', "Failed Deleting Category Comment");
            DB::rollback();
        }
    }

    public function changeStatus($commentId)
    {
        DB::beginTransaction();
        try{
            $comment = CategoryComment::where('id', $commentId)->first();
            $comment->status = strval(1 - $comment->status);
            $comment->save();
            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Category Comment State Changed",
            ]);

            DB::commit();
            session()->flash('success', "Status Successfully Changed");
        }catch(\Exception $e){
            session()->flash('danger', "Couldn't Process Your Request");
            DB::rollback();
        }
    }

    public function render()
    {
        $result = CategoryComment::select('id', 'comment', 'status')->where('category_id',$this->cat_id)->get();
        return view('livewire.category.cat-comment', ['comments' => $result])->layout('layouts.admin');
    }
}
