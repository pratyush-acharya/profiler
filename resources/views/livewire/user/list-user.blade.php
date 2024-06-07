<div>
    <h1>User List</h1>

    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->status }}</td>
                <td><a href="#" onclick="confirm('Confirm Action?') || event.stopImmediatePropagation()" wire:click="changeStatus({{ $user->id }})">change</a></td>
                <td><a href="#">View More...</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- {{ $users }} -->
</div>
