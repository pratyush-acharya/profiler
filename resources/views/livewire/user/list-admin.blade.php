<div>
@include('get-alert')

    <table class="table user-table">
        <thead>
        <tr>
            <th scope="col">S.N.</th>
            <th scope="col">Users</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($users as $user)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
            <a href="/user/edit/{{ $user->id }}"><i class="far fa-edit"></i></a>
            <form style="display: inline-block">
                <a href="#" wire:click.prevent="delete({{ $user->id }})"><i class="far fa-trash-alt"></i></a>
            </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>    
</div>
