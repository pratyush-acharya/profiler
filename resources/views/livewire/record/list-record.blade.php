<div>
    <nav>
    @for($i = 1; $i <= 8; $i++)
        <a href="/record/user/{{$userId}}/semester/{{$i}}">{{$i}}</a> | 
    @endfor
    </nav>

    <h2>Record List</h2>
    @foreach($recordList as $record)
    <ul>
        <li> {{ $record->comment }} </li>
        <ul>
            <li> - {{ $record->category->name }}</li>
            @if($record->start_date != NULL)
                <li> {{ $record->start_date }} | {{ $record->end_date }}</li>
            @endif
            
            @foreach($record->attachments as $attachment)
            <li>{{ $attachment->url }}</li>
            <li><a href="/record/attachment/{{$attachment->id}}">View</a></li>
            @endforeach

            <a href="/record/edit/{{ $record->id }}">Edit</a>
        </ul>
    </ul>
    <button type="button" wire:click.prevent="deleteRecord({{$record->id}})">DEL</button>
    <br>
    @endforeach
    <br>
    <br>
    <livewire:result.list-result :userId="$userId" :semester="$reqSem">
</div>
