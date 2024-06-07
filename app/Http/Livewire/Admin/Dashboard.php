<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

use App\Models\User;
use App\Models\Student;
use App\Models\Batch;

class Dashboard extends Component
{
    public $enrolled = 0;
    public $batches = 0;
    public $graduates = 0;

    public function mount()
    {
        // $this->enrolled = Student::with(['batch' => function ($query) {
        //     $query->where('is_grad', '0');
        // }])->get()->count();

        // $this->enrolled = Student::whereHas('batch', function ($query) {
        //     $query->where('is_grad', '0');
        // })->get()->count();
        
        $this->enrolled = Student::all()->count();

        $this->batches = Batch::count();

        $this->graduates = Student::whereHas('batch', function ($query) {
            $query->where('is_grad', '1');
        })->get()->count();            
        $this->graduates = Student::join('batches', 'students.batch_id', '=', 'students.id')->where('batches.is_grad', '1')->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
        ->layout('layouts.admin');
    }
}
