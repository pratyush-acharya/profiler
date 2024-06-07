<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;

use App\Models\Category;
use App\Models\UserLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class CreateCategory extends Component
{
    public $name;
    public $description;
    public $date_required = 0;
    public $end_date_required = 0;
    public $update = 0;

//    private $id = 0;
    public $reqId = 0;

    protected function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'date_required' => 'required|numeric|min:0|max:1',
            'end_date_required' => 'required|numeric|min:0|max:1'
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
            $category = Category::findOrFail($id);
            $this->name = $category->name;
            $this->description = $category->description;
            $this->date_required = $category->is_date;
            $this->end_date_required = $category->is_end_date;
        }
    }

    public function create()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_date' => strval($this->date_required),
                'is_end_date' => strval($this->end_date_required)
            ]);

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "New Category Created",
            ]);

            DB::commit();
            session()->flash('success', 'New Category Successfully Created');

            $this->reset(['name', 'description', 'date_required', 'end_date_required']);
            return redirect('/category/list');    
        }catch(\Exception $e){
            session()->flash('danger', "");
            DB::rollback();
        }            
    }

    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $category = Category::find($this->reqId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_date' => strval($this->date_required),
                'is_end_date' => strval($this->end_date_required)
            ]);

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => "Category Updated",
            ]);

            DB::commit();
            session()->flash('success', "Category Successfully Updated");

            return redirect('/category/list');    
        }catch(\Exception $e){
            session()->flash('danger', "Failed To Update Category");
            DB::rollback();
        }
    }

    public function render()
    {
        return view('livewire.category.create-category')->layout('layouts.admin');
    }
}
