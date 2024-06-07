<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\UserLog;



class ListAdmin extends Component
{
    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $adminCount = User::where('role', 'admin')->get()->count();
            if($adminCount > 1)
            {
                $user = User::where('role', 'admin')->where('id', $id)->firstOrFail();
                $user->delete();

                UserLog::create([
                    'user_id' => Auth::user()->id,
                    'log_detail' => "Admin User Deleted",
                ]);

                DB::commit();
                session()->flash('success', 'Admin User Successfully Deleted');
            }
        }catch(\Exception $e){
            session()->flash('danger', "Failed Deleting Admin User");
            DB::rollback();
        }
    }

    public function render()
    {
        $result = User::select('id', 'name', 'email')->where('role','admin')->get();
        return view('livewire.user.list-admin', ['users' => $result]);
    }
}
