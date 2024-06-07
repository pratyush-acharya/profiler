<div>
    <div class="container-fluid request-table">
        <h4 class="table-title">Requests</h4>
        <hr>
        <div class="container scroll-list">
        <!--One record-->
            <ul class="record-list">
                @if($requests->count() == 0)
                <p>No Recent Requests Found!!!</p>
                @endif
                @foreach($requests as $request)
                <li>
                    <div class="req-record">
                        <form class="form-inline action-btn">
                            <button class="btn btn-success btn-inline mx-1 my-2 py-1" wire:click.prevent="changeState({{ $request->id }},'solved')">Solved</button>
                            <button class="btn btn-warning btn-inline mx-1 my-2 py-1" wire:click.prevent="changeState({{ $request->id }},'ongoing')">Ongoing</button>
                            <button class="btn btn-danger btn-inline mx-1 my-2 py-1" wire:click.prevent="changeState({{ $request->id }},'rejected')">Reject</button>
                        </form>

                        <div class="img-details">
                        <img src="{{ $request->image }}" class="user-img">
                        <div class="name-title">
                            <h6>{{ $request->user->name }} - {{ $request->stream.' '.$request->year }}</h6>
                            <p class="user-admin-txt">{{ $request->message }}</p>
                        </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
