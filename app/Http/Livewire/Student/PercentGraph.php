<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;

class PercentGraph extends Component
{
    // protected $listeners = ['changePercent'];

    public $percentage;

    public function changePercent($newP)
    {
        $this->percentage = $newP;
        $this->render();
    }
    
    public function render()
    {
        $this->dispatchBrowserEvent('showChart');
        return view('livewire.student.percent-graph');
    }
}
