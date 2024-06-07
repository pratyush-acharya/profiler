<?php

namespace App\Http\Livewire\Log;

use Livewire\Component;

use App\Models\UserLog;

class RecentLogList extends Component
{
    public function render()
    {
        $result = UserLog::select('user_id', 'log_detail', 'updated_at')->orderBy('created_at', 'desc')->limit(10)->get();
        return view('livewire.log.recent-log-list', ['userlogs' => $result]);
    }
}
