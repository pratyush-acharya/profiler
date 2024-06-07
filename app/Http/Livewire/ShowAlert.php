<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowAlert extends Component
{
    protected $listeners = ['refreshAlert'];

    public function refreshAlert()
    {
        $refresh;
    }

    public function render()
    {
        return <<<'blade'
            <div>
                @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif
        
                @if (session()->has('danger'))
                <div class="alert alert-danger" role="alert">
                {{ session('danger') }}
                </div>
                @endif
        
                @if (session()->has('warning'))
                <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
                </div>
                @endif
            </div>
        blade;
    }
}
