<div>
<div class="row">
<div class="col-10 mx-auto">
            <div class="container-fluid form-container">

            <form wire:submit.prevent="@if($state == 2){{'update'}}@else{{'create'}}@endif">
                @csrf
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Enter new category" wire:model.lazy="categoryName">
                        @error('categoryName')<label class="text-danger">{{$message}}</label>@enderror
                    </div>
                    <div class="col">
                        <button class="btn btn-primary add-btn" type="submit">@if($state==2) Update @else Add @endif</button>
                    </div>
                </div>
            </form>

            <table class="table csit-table">
                <thead>
                <tr>
                    <th scope="col">S.N.</th>
                    <th scope="col">Attachment Category</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($attachmentCategories as $category)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $category->category_name }}</td>
                    <td>
                        <a href="#" wire:click="editState({{ $category->id }})"><i class="far fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>