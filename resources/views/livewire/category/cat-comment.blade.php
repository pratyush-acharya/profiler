<div>
    <div class="row">
        <div class="container-fluid">
        <br>
            <form wire:submit.prevent="@if($state == 2){{'update'}}@else{{'create'}}@endif">
                @csrf
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Enter new comment" wire:model="comment">
                    </div>
                    <div class="col">
                        <button class="btn btn-primary add-btn" type="submit">@if($state==2) Update @else Add @endif</button>
                    </div>
                </div>
            </form><br><br>

            <table class="table csit-table">
                <thead>
                <tr>
                    <th scope="col">S.N.</th>
                    <th scope="col">Comment</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($comments as $comment)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $comment->comment }}</td>
                    <td><a href="#" onclick="confirm('Do You Want To Change State?') || event.stopImmediatePropagation()" wire:click.prevent="changeStatus({{ $comment->id }})"><i class="fas fa-circle fa-lg @if($comment->status == 1) text-success @else text-danger @endif"></i></a></td>
                    <td>
                        <a href="#" wire:click="editState({{ $comment->id }})"><i class="far fa-edit"></i></a>
                        <form style="display: inline-block">
                            <a href="#" onclick="confirm('Confirm Action?') || event.stopImmediatePropagation()" wire:click="delete({{ $comment->id }})"><i class="far fa-trash-alt"></i></a>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>