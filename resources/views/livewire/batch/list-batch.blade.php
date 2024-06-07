<div>
@include('get-alert')

<div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
              <button type="button" class="btn btn-primary" style="margin: 5px" data-toggle="modal" data-target="#batchCsv">Upload Batch Create Csv file</button>
            <h4 class="table-title">Batch List</h4>
<table class="table">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">Year</th>
        <th scope="col">Stream</th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Graduated Date</th>
        <th scope="col">Present Semester</th>
        <th scope="col">Action</th>
        <th scope="col">Add Semester</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($batches as $batch)
      <tr>
        <td scope="row">{{ $loop->iteration }}</td>
        <td>{{ $batch->year }}</td>
        <td>{{ $batch->stream }}</td>
        <td>{{ $batch->start_date }}</td>
        <td>{{ $batch->end_date }}</td>
        <td>{{ $batch->grad_date }}</td>
        <td>{{ $batch->present_sem == '9'? 'Graduated' : $batch->present_sem }}</td>
        <td>
            <a href="edit/{{$batch->id}}"><i class="far fa-edit"></i></a> | 
            <a href="#" onclick="confirm('Confirm Action?') || event.stopImmediatePropagation()" wire:click="delete({{ $batch->id }})"><i class="far fa-trash-alt"></i></a>
        </td>
        <td><button class="btn btn-primary" style="width: 100px;" type="button" onclick="confirm('Do You Want To Increase Semester?') || event.stopImmediatePropagation()" wire:click.prevent="addOne({{ $batch->id }})">{{ $batch->present_sem == '8'? 'Graduate' : 'Add' }}</button></td>
      </tr>
      @endforeach
    </tbody>
  </table> 
  </div>
  </div>
  </div>

@if (count($errors) > 0)
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#batchCsv').modal('show');
        });
    </script>
@endif

  <!-- Modal -->
  <div class="modal fade" id="batchCsv" tabindex="-1" role="dialog" aria-labelledby="batchCsvLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="batchCsvLabel">Upload Batch Csv file</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('upload.batchCsv') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" name="batchCsv" id="batchCsv">
                @error('batchCsv') <span>{{ $message }}</span> @enderror
        </div>
        <a href="{{ asset('/sample/demo_batch.csv') }}" class="ml-3">Download Sample</a>
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
