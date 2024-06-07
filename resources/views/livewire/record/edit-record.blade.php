<div>
@include('get-alert')

<div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
    
    <div class="UserEntry">
        <h5>Record Form</h5>
    </div>
    
    <form wire:submit.prevent="store" enctype="multipart/form-data">
    @csrf
        @if($selectStudent == 1)
        <div class="form-group px-4">
            <label for="student">Student</label>
            <select class="form-control" id="student" wire:model="studentId">
                <option value="0">--Choose Student--</option>
                @foreach($studentList as $student)
                <option value="{{$student->id}}">{{$student->name}}</option>
                @endforeach
            </select>
            @error('student')<label class="text-danger">{{$message}}</label>@enderror
        </div>
        @else
        <div class="form-group px-4">
            <h4>{{$studentName}}</h4>
        </div>
        @endif
        <br>

        @if($studentId != 0)
        <div class="form-group px-4">
            <label for="studentSemester">Semester</label>
            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Semester" aria-describedby="button-addon2" min="1" max="8" wire:model="studentSemester" id="studentSemester" @if($editSem == 0) readonly @endif>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" wire:click.prevent="enableEdit">Edit</button>
                </div>
            </div>
            @error('studentSemester')<label class="text-danger">{{$message}}</label>@enderror
        </div>
        @endif

        <div class="form-group px-4">
            <label for="category">Category</label>
            <select class="form-control" id="category" wire:model="category">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{$category->name}}</option>
                @endforeach
            </select>
            @error('category')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="customComment">Comment</label>
            <div class="input-group">
                @if($isModify == 0)
                    <select class="form-control" id="comment" wire:model="comment">
                    <option value="0" disabled>--Choose Comment---</option>
                    @foreach($comments as $comment)
                        <option>{{ $comment->comment }}</option>
                    @endforeach
                    </select>
                @endif

                @if($isModify==1)
                    <input class="form-control" type="text" @if($isModify == 0) readonly @endif placeholder="Custom comment here..." wire:model.lazy="comment" value="{{$comment}}">
                @endif
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" wire:click.prevent="modify">Edit</button>
                    @if($isModify == 0)
                        <button class="btn btn-outline-secondary" type="button" wire:click.prevent="custom">Add</button>
                    @else
                        <button class="btn btn-outline-secondary" type="button" wire:click.prevent="listComment">Custom</button>
                    @endif
                </div>
            </div>
            @error('comment')<label class="text-danger">{{$message}}</label>@enderror    
        </div>
     
        @if($isStartDate)
            <div class="form-group px-4">
                <label for="startDate">@if($isEndDate) Start @endif Date</label>
                <div class="input-group mb-3">
                    <input type="date" id="startDate" wire:model="startDate" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" wire:click.prevent="remove(1)">X</button>
                    </div>
                </div>
                @error('startDate')<label class="text-danger">{{$message}}</label>@enderror    
            </div>  

            @if($isEndDate)
                <div class="form-group px-4">
                    <label for="endDate">End Date</label>
                    <div class="input-group mb-3">
                        <input type="date" id="endDate" wire:model="endDate" class="form-control">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" wire:click.prevent="remove(2)">X</button>
                        </div>
                    </div>
                    @error('endDate')<label class="text-danger">{{$message}}</label>@enderror    
                </div>
            @else
                <button wire:click.prevent="add(2)" class="btn btn-info mx-4">Add End-Date</button>
            @endif
        @else
            <button wire:click.prevent="add(1)" class="btn btn-info mx-4">Add Date</button>
        @endif
        <br>
        <div class="form-group px-4 mt-3">
            <label for="attachment">Record Attachment</label>
            <input type="file" multiple class="form-control" wire:model="attachments" id="attachment">
            @error('attachment')<label class="text-danger">{{$message}}</label>@enderror
            
            <br>
            @if($oldAttachments->count() > 0)
                <p>Old Attachments :: </p> 
                @foreach($oldAttachments as $attachment)
                    <span> <a class="mr-2" href="#" wire:click.prevent="downloadRecordAttachment({{$attachment->id}})">Attachemt {{ $attachment->id }}</a><button type="button" class="btn btn-danger py-0 px-2" onclick="confirm('Do You Want To Remove This Attachment?') || event.stopImmediatePropagation()" wire:click="deleteAttachment({{ $attachment->id }})">x</button></span><br>
                @endforeach   
            @endif
        </div>

        <button type="submit" class="btn btn-primary mx-4">Save</button>
    </form>
    </div>
    </div>
    </div>
</div>
