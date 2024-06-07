<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use App\Models\CategoryComment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\UserLog;

class ListCategory extends Component
{
    public $enrolled;
    public $bca;
    public $csit;
    public $categoryList;
    public $categoryId;

    public function mount()
    {
        // $this->enrolled = Student::whereHas('batch', function ($query) {
        //     $query->where('is_grad', '0');
        // })->get()->count();

        $this->enrolled = Student::all()->count();

        $this->csit = Student::whereHas('batch', function ($query) {
            $query->where('stream', 'BSc. CSIT');
        })->get()->count();

        // $this->bca = Student::whereHas('batch', function ($query) {
        //     $query->where('stream', 'BCA')->where('is_grad', '0');
        // })->get()->count();

        $this->bca = Student::whereHas('batch', function ($query) {
            $query->where('stream', 'BCA');
        })->get()->count();


        $this->categoryList = Category::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();
        if($this->categoryList->count() > 0)
            $this->categoryId = $this->categoryList->first()->id;
    }

    public function updatedCategoryId()
    {
        $this->emit('loadCustomComment', $this->categoryId); 
    }

    public function changeStatus($categoryId)
    {
        DB::beginTransaction();
        try{
            $category = Category::where('id', $categoryId)->first();
            $category->status = strval(1 - $category->status);
            $category->save();

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Category State Changed",
            ]);

            DB::commit();
            session()->flash('success', "Category State Changed Successfully");
        }catch(\Exception $e){
            session()->flash('danger', "Failed Changing Category State");
            DB::rollback();
        }
    }

    public function deleteCategory($categoryId)
    {
        DB::beginTransaction();
        try{
            $category = Category::find($categoryId);
            $categoryComments = CategoryComment::where('category_id', $category->id)->get();
            foreach($categoryComments as $categoryComment)
            {
                $categoryComment->delete();
            }
            $category->delete();

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Category Deleted",
            ]);
            
            DB::commit();
            session()->flash('success', "Category Successfully Deleted");
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Delete The Category");
            DB::rollback();
        }
    }

    public function render()
    {
        $result = Category::select('id', 'name', 'description', 'status')->orderBy('name', 'asc')->get();
        return view('livewire.category.list-category', ['categories' => $result])->layout('layouts.admin');
    }
}
