<div>
    @include('get-alert')
    <div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
    <form wire:submit.prevent="store" enctype="multipart/form-data">
        <div class="form-group px-4">
            <label for="batch">Batch</label>
            <select class="form-control" id="batch" wire:model="batch">
            @foreach($batchList as $batch)
                <option value="{{ $batch->id }}">{{$batch->year . "(". $batch->year .")" }}</option>
            @endforeach
            </select>
            @error('batch')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="semester">Semester</label>
                <select name="semester" id="semester" class="form-control" wire:model="semester">
                    @for($i=1; $i< 9;$i++)
                        <option value="{{ $i }}">Semester {{$i}}</option>
                    @endfor
                </select>
            @error('semester')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="csv">Upload Details CSV</label>
            <input type="file" name="csv" id="csv" class="form-control" wire:model="csvFile">
            <div wire:loading wire:target="csvFile">Uploading...</div>
            @error('csv')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="zip">Upload ZIP</label>
            <input type="file" name="zip" id="zip" class="form-control" wire:model="zipFile">
            <div wire:loading wire:target="zipFile">Uploading...</div>
            @error('zip')<label class="text-danger">{{$message}}</label>@enderror
        </div>
        
        <a href="{{ asset('/sample/boardResultBulk.csv') }}" class="ml-3">Download Board Sample</a>
        <a href="{{ asset('/sample/reBoardResultBulk.csv') }}" class="ml-3">Download Re-Board Sample</a><br>

        <button type="submit" class="btn btn-primary ml-4">Save</button>
</div>
