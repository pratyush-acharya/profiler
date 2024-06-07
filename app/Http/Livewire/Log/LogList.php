<?php

namespace App\Http\Livewire\Log;

use Livewire\Component;

use App\Models\UserLog;

class LogList extends Component
{
    public function render()
    {
        $result = UserLog::select('user_id', 'log_detail', 'updated_at')->orderBy('updated_at', 'desc')->limit(100)->get();
        return view('livewire.log.log-list', ['userlogs' => $result])->layout('layouts.admin');
    }
}
