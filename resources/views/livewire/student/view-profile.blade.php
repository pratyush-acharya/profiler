<div>
@include('get-alert')

<div class="row">
  <div class="col-md-12">
    <div class="container-fluid student_details">
      <div class="col-md-9 student_content">
        <h3 class="table-title font-weight-bold ">{{ $authUser->name }} </h3>
        <p> Class of {{ $authUser->student->batch->year." (".$authUser->student->batch->stream.")" }} </p>
        @if($authUser->student->batch->present_sem == 9)
          <p>Graduated</p>
        @else
          <p> Currently studying in Semester {{$authUser->student->batch->present_sem }} </p>
        @endif

        <a href="/user/edit/{{ $authUser->id }}">
          <label class="edit">Edit Profile <i class="fas fa-long-arrow-alt-right"></i></label>
        </a>
        <a href="/student/recordCollection/{{$authUser->id}}">
          <label class="download">Download CV <i class="fas fa-long-arrow-alt-right"></i></label>
        </a>
        <a href="/record/create/{{ $authUser->id }}">
          <label class="download">Add Record <i class="fas fa-long-arrow-alt-right"></i></label>
        </a>
        <a href="/result/create/{{ $authUser->id }}">
          <label class="download">Add Result <i class="fas fa-long-arrow-alt-right"></i></label>
        </a>
        <a href="/student/attachment/{{ $authUser->id }}">
          <label class="download">View Attachments <i class="fas fa-long-arrow-alt-right"></i></label>
        </a>
        <a href="/student/boardResult/{{ $authUser->id }}">
          <label class="download" style="margin-left:0px;">View Board Results <i class="fas fa-long-arrow-alt-right"></i></label>
        </a>
      </div>
      <div class="col-md-3 s">
        <img class="card-img-top img-fluid" src="{{ $authUser->student->image }}">
      </div>
    </div>
  </div>
</div>

<!--Data Panel -->

<div class="row">
<!--Semester Rank-->
  <div class="col-md-6">
    <div class="container-fluid datapanel">
      <div class="col-md-12 ">
        <h5 class="table-title font-weight-bold ">Semester Ranks </h5>
        <div class="row">
          <div class="col-md-4">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="semdropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 50px;" wire:key="{{rand()}}">
                {{ 'Semester'. " " .$presentSem }}
              </button>
              <div class="dropdown-menu" aria-labelledby="semdropdownMenuButton">
                @for($i = 1; $i < 9; $i++)
                  <a class="dropdown-item" href="#" wire:click.prevent="changeSemTo({{$i}})">{{ 'Semester '.$i }}</a>
                @endfor
              </div>
            </div>
          </div>
          @if(!empty($result))
            @if(!$result->mid_percentage || !$result->final_percentage || !$result->board_percentage)
            <div class="col-md-8">
              <p style="color: red;">Please contact Administration for further details.</a></p>
            </div>
            @endif
          @endif
          @if(empty($result))
           <div class="col-md-8">
            <p style="color: red;">Please contact Administration for further details.</p>
          </div>
          @endif
        </div>
        
        <div class="row">
          <div class="col-md-12 mt-1">
            <div class="skills-area">
              @if(!empty($result))
                <livewire:student.percent-graph :percentage='$result->mid_percentage' key="{{ rand() }}">
                <livewire:student.percent-graph :percentage='$result->final_percentage' key="{{ rand() }}">
                <livewire:student.percent-graph :percentage='$result->board_percentage' key="{{ rand() }}">                
              @else
                <livewire:student.percent-graph :percentage='"N/A"' key="{{ rand() }}">
                <livewire:student.percent-graph :percentage='"N/A"' key="{{ rand() }}">
                <livewire:student.percent-graph :percentage='"N/A"' key="{{ rand() }}">                
              @endif
            </div>
          </div>
        </div>
          
        <div class="row progressdet">
          @if(!empty($result))

          @if(!$result->mid_percentage)
            <p class="mid"><a target="_blank" href="/admin/result/{{$result->id}}/1" wire:click.prevent="getResult({{$result->id}}, 1)" style="color: inherit;"><span class="text-danger">*</span> Mid-Term</a></p>
          @else
            <p class="mid"><a target="_blank" href="/admin/result/{{$result->id}}/1" wire:click.prevent="getResult({{$result->id}}, 1)" style="color: inherit;">Mid-Term</a></p>
          @endif
            <p class="final"><a target="_blank" href="/admin/result/{{$result->id}}/2" wire:click.prevent="getResult({{$result->id}}, 2)" style="color: inherit;">Final-Term</a></p>
            <p class="board"><a target="_blank" href="/admin/result/{{$result->id}}/3" wire:click.prevent="getResult({{$result->id}}, 3)" style="color: inherit;">Board</a></p> 
          @else
            <p class="mid"><a href="#" style="color: inherit;">Mid-Term</a></p>
            <p class="final"><a href="#" style="color: inherit;">Final-Term</a></p>
            <p class="board"><a href="#" style="color: inherit;">Board</a></p>
          @endif
        
        </div>  
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="container-fluid datapanel">
      <div class="col-md-12 ">
        <h5 class="table-title font-weight-bold ">Achievements </h5>
        <div class="row">
          <div class="col-md-9">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="catdropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" wire:key="{{rand()}}">
                Category
              </button>
              <div class="dropdown-menu" aria-labelledby="catdropdownMenuButton">                
                <a class="dropdown-item" href="#" wire:click.prevent="changeCategoryTo(0)">All</a>  
                @foreach($categoryList as $category)
                  <a class="dropdown-item" href="#" wire:click.prevent="changeCategoryTo({{$category->id}})">{{$category->name}}</a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <!------->

      <div class="col-md-12">
        <div class="container scroll-list-profile">
          <ul class="record-list">
            @foreach($records as $record)
            <li>
              <div class="req-record">
                <div class="img-details">
                  <img src="/images/logo.png" class="user-img">
                  <div class="name-title">
                    <h6>{{ $record->category->name }} <a href="/record/edit/{{ $record->id }}"><i class="fas fa-edit"></i></a> | <a href="#" onclick="confirm('Do you want to delete this record?') || event.stopImmediatePropagation()" wire:click.prevent="deleteRecord({{ $record->id }})"><i class="fas fa-trash"></i></a></h6>
                    <!-- <p class="user-admin-txt">@if($record->comment == "NULL") {{ $record->category->description }} @else {{ $record->comment }} @endif @if(count($record->attachments) > 0) <a href="#"  wire:click.prevent="downloadRecordAttachment({{$record->id}})"><i class="fas fa-paperclip"></i></a>@endif -->
                    <p class="user-admin-txt">@if($record->comment == "NULL") {{ $record->category->description }} @else {{ $record->comment }} @endif @if(count($record->attachments) > 0) @foreach($record->attachments as $attachment) <a href="#" wire:click.prevent="downloadRecordAttachment({{$attachment->id}})"><i class="fas fa-paperclip"></i></a>@endforeach @endif
                    @if($record->start_date != NULL)
                    <br>
                    {{ $record->start_date. " : ". $record->end_date }}
                    @endif
                    </p>
                  </div>
                </div>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
      <!-- -->
  </div>
</div>
<!-- <div class="row">
  <div class="col-md-12">
    <div class="container-fluid rounded">
      {!! $chart->container() !!}
    </div>
  </div>
</div> -->
<div class="row">
  <div class="col-md-12">
    <div class="container-fluid rounded">
      {!! $bar->container() !!}
    </div>
  </div>
</div>
<script src="{{ $chart->cdn() }}" wire:key="{{ rand() }}"></script>
{{ $chart->script() }}
<script src="{{ $bar->cdn() }}" wire:key="{{rand()}}"></script>
{{ $bar->script() }}

<script>
     $(document).ready(function(){
        $('.dropdown-toggle').dropdown()
    });
</script>