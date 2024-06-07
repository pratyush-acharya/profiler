<div>
@include('get-alert')

    <div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
                <form wire:submit.prevent="@if($isUpdate){{'update'}}@else{{'store'}}@endif" enctype="multipart/form-data">
                    @csrf
                    @if($isUpdate == 1)
                        @method('PATCH')
                    @endif
                    <div class="form-group px-4">
                            <label for="name">Fullname</label>
                            <input type="text" class="form-control" id="name" placeholder="fullname" wire:model.lazy="name">
                            @error('name')<label class="text-danger">{{$message}}</label>@enderror
                    </div>

                    <div class="form-group px-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="email address" wire:model.lazy="email">
                            @error('email')<label class="text-danger">{{$message}}</label>@enderror
                    </div>

                    <div class="form-group px-4">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" wire:model="role">
                            <option value="1">Student</option>
                            <option value="2">Admin</option>
                        </select>
                        @error('role')<label class="text-danger">{{$message}}</label>@enderror
                    </div>
            

                    @if($role == '1')
                        <div class="form-group px-4">
                                <label for="roll">Roll</label>
                                <input type="number" class="form-control" id="roll" placeholder="roll number" wire:model.lazy="roll">
                                @error('roll')<label class="text-danger">{{$message}}</label>@enderror
                        </div>

                        <div class="form-group px-4">
                            <label for="image">Image URL</label>
                            <input type="url" class="form-control" id="image" placeholder="doko link" wire:model.lazy="image">
                            @error('image')<label class="text-danger">{{$message}}</label>@enderror
                        </div>

                        <!-- <div class="form-group px-4">
                            <label for="applicationForm">Application Form</label>
                            <input type="file" class="form-control" id="application_form" placeholder="attach application form" wire:model="application_form">
                            @error('application_form')<label class="text-danger">{{$message}}</label>@enderror
                        </div> -->

                        <div class="form-group px-4">
                            <label for="batch">Batch</label>
                            <select class="form-control" id="batch" wire:model="batch">
                                @foreach($batchList as $batch)
                                <option value="{{$batch->id}}">{{$batch->year}}({{$batch->stream}})</option>
                                @endforeach
                            </select>
                            @error('role')<label class="text-danger">{{$message}}</label>@enderror
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary mx-4">@if($isUpdate) Update @else Create @endif</button>
                {{ $application_form }}
                </form>
            </div>
        </div>
    </div>
</div>
