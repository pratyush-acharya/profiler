<div>
@include('get-alert')

    <div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
                <form wire:submit.prevent="@if($update){{'update'}}@else{{'store'}}@endif" enctype="multipart/form-data">
                    @csrf
                    @if($update == 1)
                        @method('PATCH')
                    @endif

                    <div class="form-group px-4">
                        <label for="year">Batch Year</label>
                        <input type="number" class="form-control" id="year" placeholder="YYYY" min="2015" max="2100" wire:model.lazy="year">
                        @error('year')<label class="text-danger">{{$message}}</label>@enderror
                    </div>

                    <div class="form-group px-4">
                        <label for="stream">Stream</label>
                        <select class="form-control" id="stream" wire:model="stream">
                            <option value="BSc. CSIT">BSc. CSIT</option>
                            <option value="BCA">BCA</option>
                        </select>
                        @error('stream')<label class="text-danger">{{$message}}</label>@enderror
                    </div>

                    <div class="form-group px-4">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" placeholder="Start Date" wire:model.lazy="start_date">
                        @error('start_date')<label class="text-danger">{{$message}}</label>@enderror    
                    </div>
                    
                    <div class="form-group px-4">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" placeholder="End Date" wire:model.lazy="end_date">
                        @error('end_date')<label class="text-danger">{{$message}}</label>@enderror    
                    </div>
                    
                    <div class="form-group px-4">
                        <label for="grad_date">Graduation Date</label>
                        <input type="date" class="form-control" id="grad_date" placeholder="Graduation Date" wire:model.lazy="grad_date">
                        @error('grad_date')<label class="text-danger">{{$message}}</label>@enderror    
                    </div>

                    <div class="form-group px-4">
                        <label for="present_sem">Present Semester</label>
                        <select class="form-control" id="present_sem" wire:model="present_sem">
                            @for($i=1; $i<=8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                            <option value="9">Graduated</option>
                        </select>
                        @error('present_sem')<label class="text-danger">{{$message}}</label>@enderror
                    </div>
                                        
                    <button type="submit" class="btn btn-primary mx-4">@if($update) Update @else Create @endif</button>
                </form>      
            </div>
        </div>
    </div> 
</div>
