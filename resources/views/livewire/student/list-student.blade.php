<div>
@include('get-alert')

    <div class="row three-labels">

    <div class="col-md-3 dash-stat1 mx-auto">
        <div class="numbers">
            <h3 class="text-center csit-header">{{ $stream }} ({{$year}})</h3>
        </div>

    </div>

    <div class="col-md-3 dash-stat2 mx-auto">
        <div class="numbers">
            <h3 class="text-center csit-header">Students : {{ $studentCount }}</h3>
        </div>

    </div>

    <div class="col-md-3 dash-stat3 mx-auto">
        <div class="numbers">
            <h3 class="csit-header">@if($semester == 9) Graduated @else SEMESTER {{ $semester }} @endif</h3>
        </div>
    </div>
    </div>

    <div class="row">
    <div class="container-fluid">
    <form>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Stream</label>
                <select class="form-control" wire:model="reqStream">
                    <option value="BSc. CSIT">BSc. CSIT</option>
                    <option value="BCA">BCA</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Class</label>
                <select class="form-control" wire:model="reqYear">
                    @if(!empty($batchList))
                    @foreach($batchList as $batch)
                        <option value={{ $batch->year }}>{{ $batch->year }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group col-md-6">
                <label></label>
                <button class="btn btn-primary show-btn" wire:click.prevent="show()">Show</button>
                <a class="btn btn-primary show-btn" href="/user/create">Add Student</a>
                <button type="button" class="btn btn-primary show-btn" data-toggle="modal" data-target="#studentCsv">Bulk Upload</button>
            </div>
        </div>
    </form>
    </div>
    </div>

    <div class="row">
    <div class="container-fluid">
        <h6>Students</h6>
        <!-- {{$debug}} -->
        <form class="form-inline d-flex md-form form-sm mt-0">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Search using name or email"
                aria-label="Search" wire:model="search">
        </form>
        <br>
        <table class="table csit-table">
            <thead>
                <tr>
                    <th scope="col">S.N.</th>
                    <th scope="col">Roll No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Year</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @if($students->count() > 0)
                @foreach($students as $student)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $student->roll }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->year }}({{ $student->stream }})</td>
                    <td>
                        <a href="/user/edit/{{ $student->id }}"><i class="far fa-edit"></i></a>
                        <form style="display: inline-block">
                            <a href="#" wire:click.prevent="changeStatus({{$student->id}})"><i class="fas fa-circle  @if($student->status == 1) text-success @else text-danger @endif"></i></a>
                            <a href="/student/profile/{{ $student->id }}"><i class="fas fa-eye"></i></a>
                            <a href="/record/create/{{ $student->id }}"><i class="fas fa-plus"></i></a>
                            <a href="/result/create/{{ $student->id }}"><i class="fas fa-chart-line"></i></a>
                            <!-- <a href="#"><i class="far fa-trash-alt"></i></a> -->
                        </form>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <th colspan=6 class="text-center">No Data Found!!!</th>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    </div>

@if (count($errors) > 0)
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#studentCsv').modal('show');
        });
    </script>
@endif

  <!-- Modal -->
    <div class="modal fade" id="studentCsv" tabindex="-1" role="dialog" aria-labelledby="studentCsvLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentCsvLabel">Upload Student Csv file</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('upload.studentCsv') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="file" name="studentCsv" id="studentCsv">
                        @error('studentCsv') <span>{{ $message }}</span> @enderror
                    </div>
                    
                    <a href="{{ asset('/sample/demo_student.csv') }}" class="ml-3">Download Sample</a>
                
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Upload
                        </button>
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
