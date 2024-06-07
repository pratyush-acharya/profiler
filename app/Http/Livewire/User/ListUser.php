<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ListUser extends Component
{
    public function changeStatus($userId)
    {
        DB::beginTransaction();
        try{
            $user = User::where('id', $userId)->first();
            $user->status = strval(1 - $user->status);
            $user->save();

            UserLog::create([
                'user_id' => Auth::user()->id,
                'log_detail' => 'Status Changed',
            ]);
            DB::commit();
            session()->flash('success', "User Status Successfully Updated");
        }catch(\Exception $e){
            session()->flash('danger', "Failed Updating User Status");
            DB::rollback();
        }
    }

    public function render()
    {
        $result = User::select('id', 'name', 'email', 'role', 'status')->get();
        return view('livewire.user.list-user', ['users' => $result]);
    }
}
