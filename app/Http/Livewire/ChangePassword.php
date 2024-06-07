<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $user;

    protected $rules = [
        'current_password'=>'required',
        'new_password'=>'required|min:5',
        'new_password_confirmation'=>'required |same:new_password'
    ];

    public function changePassword(){

        $this->validate();
        $user = User::find(Auth::user()->id);

        if(!\Hash::check($this->current_password, $user->password)){

            return back()->with('warning','You have entered wrong password');

       }else{
           if(\Hash::check($this->new_password, $user->password)){
                return redirect()->back()->with("warning","New Password cannot be same as your current password. Please choose a different password.");
           }
           else{
                $user->update([
                    'password' => \Hash::make($this->new_password_confirmation, [12]),
                ]);
                session()->flash('success', "Password has been successfully updated!");
                return redirect('/admin');
           }
       }

       $this->reset(['current_password', 'new_password','new_password_confirmation']);
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
