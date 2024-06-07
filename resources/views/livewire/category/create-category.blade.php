<div>
@include('get-alert')

<div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
    <form wire:submit.prevent="@if($update){{'update'}}@else{{'create'}}@endif" enctype="multipart/form-data">
        @csrf
        <div class="form-group px-4">
            <label for="name">Category Name</label>
            <input type="text" class="form-control" id="name" placeholder="Category name" wire:model.lazy="name">
            @error('name')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label for="description">Category Description</label>
            <textarea class="form-control" placeholder="Enter category description here..." name="description" id="description" rows="2" wire:model="description"></textarea> 
            @error('description')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        <div class="form-group px-4">
            <label>Date: </label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="date_required" id="required1" value="1" wire:model="date_required">
                <label class="form-check-label" for="required1">Required</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="date_required" id="unrequired1" value="0" wire:model="date_required" checked>
                <label class="form-check-label" for="unrequired1">Not Required</label>
            </div>
        </div>

        <div class="form-group px-4">
            <label>End Date: </label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="end_date_required" id="required2" value="1" wire:model="end_date_required">
                <label class="form-check-label" for="required2">Required</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="end_date_required" id="unrequired2" value="0" wire:model="end_date_required" checked>
                <label class="form-check-label" for="unrequired2">Not Required</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mx-4">@if($update) Update @else Create @endif</button>
    </form>
    </div>
    </div>
    </div>
</div>
