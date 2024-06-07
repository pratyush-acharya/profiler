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
            <label for="fromBatch">From Batch</label>
            <select class="form-control" id="fromBatch" wire:model="fromBatch">
                @foreach($fromBatchList as $fromBatch)
                <option value="{{$fromBatch->id}}">{{$fromBatch->year}}({{$fromBatch->stream}})</option>
                @endforeach
            </select>
            @error('fromBatch')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="student">Student</label>
            <select class="form-control" id="student" wire:model="studentId">
                <option value="0">--Choose Student--</option>
                @foreach($studentList as $student)
                <option value="{{$student->id}}">{{$student->name}}</option>
                @endforeach
            </select>
            @error('studentId')<label class="text-danger">{{$message}}</label>@enderror
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
        </div>

        <button type="submit" class="btn btn-primary ml-4">Save</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#recordCsv">Bulk Upload</button>
    </form>
    </div>
    </div>
    </div>

    @if (count($errors) > 0)
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#recordCsv').modal('show');
        });
    </script>
    @endif
        <!-- Modal -->
        <div class="modal fade" id="recordCsv" tabindex="-1" role="dialog" aria-labelledby="recordCsvLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordCsvLabel">Upload Attachment Csv file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('upload.recordCsv') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label for="recordCsv">Upload Student List</label>
                    <br>
                    <input type="file" name="recordCsv" id="recordCsv">
                    <br>
                    <a href="{{ asset('/sample/demo_record.csv') }}">Download Zip Sample</a>
                    <a href="{{ asset('/sample/demo_record_pdf.csv') }}" class="ml-5">Download PDF Sample</a>
                    @error('recordCsv') <span class="text-danger">{{ $message }}</span> @enderror
                    <br>
                    <br>
                    <label for="attType">Attachment Type</label>
                    <select name="attType" id="attType" class="form-control">
                        <option value="zip">ZIP</option>
                        <option value="pdf">PDF</option>
                    </select>
                    @error('attType') <span class="text-danger">{{ $message }}</span> @enderror

                    <br>
                    <label for="attachmentCollection">Upload Attachment</label>
                    <br>
                    <input type="file" name="attachmentCollection" id="attachmentCollection">
                    <br>
                    **Please Make Sure PDF is in same order as CSV
                    @error('attachmentCollection')<span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    Upload
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>
            </div>
            </div>
        </div>
    </div>
</div>
