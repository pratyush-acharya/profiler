<div>
@include('get-alert')

<div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
    
    <div class="UserEntry">
        <h5>Attachment Form</h5>
    </div>
    <form wire:submit.prevent="@if($update){{'update'}}@else{{'save'}}@endif" enctype="multipart/form-data">
        @csrf
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
            <select class="form-control" id="student" wire:model="user_id">
                <option value="0">--Choose Student--</option>
                @foreach($studentList as $student)
                <option value="{{$student->id}}">{{$student->name}}</option>
                @endforeach
            </select>
            @error('user_id')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="type">Attachment Category</label>
            <select class="form-control" id="type" wire:model="type">
                <option value="0">--Choose Category--</option>
                @foreach($categoryList as $category)
                <option value="{{$category->id}}">{{$category->category_name}}</option>
                @endforeach
            </select>
            @error('type')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="student">Upload File</label>
            <input type="file" name="attachment" id="attachment" wire:model="attachment">
            @error('attachment') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary ml-4">@if($update) Change @else Upload @endif</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachmentCsv">Bulk Upload</button>
        <br>
    </form>
    </div>
    </div>
    </div>

    <livewire:attachment.attachment-categoory>

    @if (count($errors) > 0)
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#attachmentCsv').modal('show');
        });
    </script>
    @endif
    <!-- Modal -->
    <div class="modal fade" id="attachmentCsv" tabindex="-1" role="dialog" aria-labelledby="attachmentCsvLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attachmentCsvLabel">Upload Attachment Csv file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('upload.attachmentCsv') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label for="attachmentCsv">Upload Student List</label>
                    <br>
                    <input type="file" name="attachmentCsv" id="attachmentCsv">
                    <a href="{{ asset('/sample/demo_extra_attachment_new.csv') }}" class="ml-3">Download Sample</a><br>
                    @error('attachmentCsv') <span>{{ $message }}</span> @enderror

                    <br>
                    <label for="collectionZip">Upload ZIP</label>
                    <br>
                    <input type="file" name="collectionZip" id="collectionZip">
                    @error('collectionZip') <span>{{ $message }}</span> @enderror
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
