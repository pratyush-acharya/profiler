<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

use App\Models\User;
use App\Models\Student;
use App\Models\Attachment;
use App\Models\Batch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

use App\Models\UserLog;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;




class CreateUser extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $role = '1';
    public $roll;
    public $image;
    public $application_form;
    public $batch = '1';
    public $reqId = 0;

    public $isUpdate = 0;

    protected function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $this->reqId,
            'roll' => 'nullable|required_if:role,==,1|numeric',
            'role' => 'required|in:1,2'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private $userRole = ['1' => 'student', 2 => 'admin']; 
    private $password = "deerwalk";

    public function mount($id = 0)
    {
        if($id != 0)
        {
            $this->reqId = $id;
            $this->isUpdate = 1;
            $user = User::findOrFail($id);
            $this->name = $user->name;
            $this->email = $user->email;
            $role = $user->role;
            if($role == 'admin')
            {
                $this->role = 2;
            }else{
                $this->role = 1;
                $student = Student::where('user_id', $id)->firstOrFail();
                $this->roll = $student->roll;
                $this->image = $student->image;
                $this->batch = $student->batch_id;
            }            
        }else{
            $temp = Batch::orderBy('created_at', 'desc')->first();
            if(!empty($temp))
                $this->batch = Batch::orderBy('created_at', 'desc')->first()->id;
        }
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();
        try{
            if($this->role == '2')
            {
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password, [12]),
                    'role' => $this->userRole[$this->role]
                ]);

                UserLog::create([
                    'user_id' => Auth::user()->id,
                    'log_detail' => "New Admin Added",
                ]);

                DB::commit();
                session()->flash('success', 'New Admin Added');
            }else{
                $user = new User();
                $user->name = $this->name;
                $user->email = $this->email;
                $user->password = Hash::make($this->password, [12]);
                $user->role = $this->userRole[$this->role];
                $user->save();

                $student = new Student();
                $student->user_id = $user->id;
                $student->roll = $this->roll;
                $student->image = $this->image;
                if($this->application_form != NULL)
                    $student->application_form = $this->application_form->store('application');
                $student->batch_id = $this->batch;
                $student->save();

                UserLog::create([
                    'user_id' => Auth::user()->id,
                    'log_detail' => "New Student Added",
                ]);

                DB::commit();
                session()->flash('success', 'New Student Added');
            }

            $this->reset(['name', 'email', 'role', 'roll', 'image', 'application_form', 'batch']);
            $this->application_form = null;
    
            return redirect('/student/list');
        }catch(\Exception $e){
            session()->flash('danger', "Failed Creating User");
            DB::rollback();
        }
    }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try{
            if($this->role == '2')
            {
                $user = User::find($this->reqId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password, [12]),
                    'role' => $this->userRole[$this->role]
                ]);

                UserLog::create([
                    'user_id' => Auth::user()->id,
                    'log_detail' => "User Detail Updated",
                ]);
                
                DB::commit();
                session()->flash('success', 'Admin Details Updated');
            }else
            {
                $user = User::find($this->reqId);
                $user->name = $this->name;
                $user->email = $this->email;
                $user->password = Hash::make($this->password, [12]);
                $user->role = $this->userRole[$this->role];
                $user->save();

                $student = Student::where('user_id', $this->reqId)->firstOrFail();
                $student->roll = $this->roll;
                $student->image = $this->image;
                if($this->application_form != NULL){
                    Storage::delete($student->application_form);
                    $student->application_form = $this->application_form->store('application');
                }
                $student->batch_id = $this->batch;
                $student->save();

                UserLog::create([
                    'user_id' => Auth::user()->id,
                    'log_detail' => "User Detail Updated",
                ]);

                DB::commit();
                session()->flash('success', 'Student Detail Updated');
            }

            return redirect('/student/list');
        }catch(\Exception $e){
            session()->flash('danger', "");
            DB::rollback();
        }
    }

    public function render()
    {
        $batchList = Batch::select('id', 'year', 'stream')->get();
        return view('livewire.user.create-user', ['batchList' => $batchList])->layout('layouts.admin');
    }
}
