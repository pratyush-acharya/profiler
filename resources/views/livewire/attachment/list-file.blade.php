<div>
    <h1>File List</h1>
    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th>User Id</th>
                <th>Category Id</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($files as $file)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{$file->user_id}}</a></td>
                <td>{{ $file->type }}</td>
                <td><a href="edit/{{$file->id}}">Change</a> | <a href="#" wire:click="export({{ $file->url }})">Download</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
