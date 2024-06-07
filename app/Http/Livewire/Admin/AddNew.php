<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class AddNew extends Component
{
    public function render()
    {
        return view('livewire.admin.add-new')->layout('layouts.admin');
    }
}
