<div>
@include('get-alert')


<div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
    <form wire:submit.prevent="@if($updateResult == 1){{'update'}}@else{{'store'}}@endif" enctype="multipart/form-data">
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
            @error('student')<label class="text-danger">{{$message}}</label>@enderror
        </div>
        @else
        <div class="form-group px-4">
            <h4>{{$studentName}}</h4>
        </div>
        @endif

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

        <hr class="bg-warning mx-4 ">
        <div class="form-group px-4">
            <label for="midStatus">Mid Term Status</label>
            <select class="form-control" id="midStatus" wire:model="midStatus">
                <option value="pass">Pass</option>
                <option value="fail">Fail</option>
            </select>
            @error('midStatus')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="midPercentage">Mid Term Percentage</label>
            <input type="number" class="form-control" id="midPercentage" placeholder="00.00" wire:model.lazy="midPercentage" step="0.01">
            @error('midPercentage')<label class="text-danger">{{$message}}</label>@enderror    
        </div>

        <div class="form-group px-4">
            <label for="midAttachment">Mid Term Attachment</label>
            <div class="form-inline">
                <input type="file" class="form-control" id="midAttachment" wire:model="midAttachment">
                <button class="btn btn-danger" id="midAttachment-file-reset" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delResultAttachment(1)"  style="margin: 5px 5px;" type="button">Reset file</button>
            </div>
            @error('midAttachment')<label class="text-danger">{{$message}}</label>@enderror    
        </div>

        <br>
        <hr class="bg-warning mx-4 ">
    
        <div class="form-group px-4">
            <label for="finalStatus">Final Term Status</label>
            <select class="form-control" id="finalStatus" wire:model="finalStatus">
                <option value="pass">Pass</option>
                <option value="fail">Fail</option>
            </select>
            @error('finalStatus')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="finalPercentage">Final Term Percentage</label>
            <input type="number" class="form-control" id="finalPercentage" placeholder="00.00" wire:model.lazy="finalPercentage" step="0.01">
            @error('finalPercentage')<label class="text-danger">{{$message}}</label>@enderror    
        </div>

        <div class="form-group px-4">
            <label for="finalAttachment">Final Term Attachment</label>
            <div class="form-inline">
                <input type="file" class="form-control" id="finalAttachment" wire:model="finalAttachment">
                <button class="btn btn-danger" id="finalAttachment-file-reset" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delResultAttachment(2)"  style="margin: 5px 5px;" type="button">Reset file</button>
            </div>
            @error('finalAttachment')<label class="text-danger">{{$message}}</label>@enderror    
        </div>

        <br>
        <hr class="bg-warning mx-4 ">

        <div class="form-group px-4">
            <label for="boardStatus">Board Term Status</label>
            <select class="form-control" id="boardStatus" wire:model="boardStatus">
                <option value="pass">Pass</option>
                <option value="fail">Fail</option>
            </select>
            @error('boardStatus')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="boardPercentage">Board Percentage</label>
            <input type="number" class="form-control" id="boardPercentage" placeholder="00.00" wire:model.lazy="boardPercentage" step="0.01">
            @error('boardPercentage')<label class="text-danger">{{$message}}</label>@enderror    
        </div>

        <div class="form-group px-4">
            <label for="boardAttachment">Board Attachment</label>
            <div class="form-inline">
                <input type="file" class="form-control" id="boardAttachment" wire:model.lazy="boardAttachment">
                <button class="btn btn-danger" id="boardAttachment-file-reset" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delResultAttachment(3)"  style="margin: 5px 5px;" type="button">Reset file</button>
            
                <div class="form-inline">
                    <input type="checkbox" class="form-check-input" id="backPaperStatus" checked value="1" wire:model.lazy="backPaperStatus">
                    <label for="backPaperStatus" class="form-check-label" style="color:black;">Back Paper</label> 
                </div>

            </div>
            @error('boardAttachment')<label class="text-danger">{{$message}}</label>@enderror    
        </div>
        <!-- ---------------------------------- -->

        
        <button type="submit" class="btn btn-primary ml-4">Save</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#resultCsv">Bulk Upload</button>
        <a href="/result/bulkBoard"><button type="button" class="btn btn-primary">Bulk Upload(Board)</button></a>
    </form>
    </div>
    </div>
    </div>
    
    @if (count($errors) > 0)
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#resultCsv').modal('show');
        });
    </script>
    @endif

        <!-- Modal -->
        <div class="modal fade" id="resultCsv" tabindex="-1" role="dialog" aria-labelledby="resultCsvLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultCsvLabel">Upload Result Csv file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('upload.resultCsv') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label for="examDate">Exam Date</label>
                    <input type="text" id="examDate" name="examDate" placeholder="JANUARY 2018" class="form-control">
                    <br>

                    <label for="examType">Exam Type</label>
                    <select name="examType" id="examType" class="form-control">
                        <option value="MID TERM">Mid Term</option>
                        <option value="FINAL TERM">Final Term</option>
                    </select>
                    <br>

                    <label for="examSem">Semester</label>
                    <select name="examSem" id="examSem" class="form-control">
                        @for($i=1; $i< 9;$i++)
                            <option value="{{ $i }}">Semester {{$i}}</option>
                        @endfor
                    </select>
                    <br>

                    <label for="publishDate">Publish Date</label>
                    <input type="text" id="publishDate" name="publishDate" placeholder="25th January, 2021" class="form-control">
                    <br>

                    <label for="grade">Grade</label>
                    <input type="text" id="grade" name="grade" placeholder="Freshman Year - SEM I" class="form-control">
                    <br>
                    <label for="recordCsv">Upload Result CSV</label>
                    <br>
                    <input type="file" name="resultCsv" id="resultCsv" class="form-control">
                    <br>
                    <a href="{{ asset('/sample/demo_result.csv') }}" class="ml-3">Download Sample</a><br>
                    @error('recordCsv') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <!-- <p class="ml-3">*Note: Please make sure the order of csv record and pdf is same !!</p> -->
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

