<div>
@include('get-alert')
    <div class="container-fluid csit-table">
        <h4 class="table-title">Bsc. CSIT</h4><hr>
        <div class="container batch-list">
        <ul class="record-list">
            @foreach($batches as $batch)
            <li>
                <div class="req-record">
                    <div class="batch-text">
                    <h6>{{ $batch->year }}</h6>
                    </div>
                    <a href="/batch/edit/{{ $batch->id }}" class=""><i class="far fa-edit"></i></a>
                    
                    <form style="display: inline-block">
                    <a href="#" wire:click.prevent="delete({{ $batch->id }})"><i class="far fa-trash-alt"></i></a>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
        </div>

        <div class="add-batch">
        <a href="/batch/create"><h6 class="text-center">Add Batch</h6></a>
        </div>
    </div>
</div>
