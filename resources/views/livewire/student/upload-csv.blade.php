<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form wire:submit.prevent="{{'upload'}}" enctype="multipart/form-data">
        <label>Batch:</label>
        <select name="batch" id="batch" wire:model="batch">
            <option selected disabled>--Choose Batch--</option>
            @foreach($batchList as $batch)
            <option value="{{$batch->id}}">{{$batch->year}}({{$batch->stream}})</option>
            @endforeach
        </select>
        <br>
        <label>Upload file:</label>
        <input type="file" name="attachment" id="attachment" wire:model="attachment">

        @error('attachment') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Upload</button>
        <br>
    </form>
</div>
