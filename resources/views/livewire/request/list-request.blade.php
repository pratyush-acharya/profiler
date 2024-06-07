<div>
@include('get-alert')

<div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">

            <h4 class="table-title">Request List</h4>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">S.N</th>
            <th scope="col">User</th>
            <th scope="col">Class</th>
            <th scope="col">State</th>
            <th scope="col">Message</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($requests as $request)
          <tr>
            <td  scope="row">{{ $loop->iteration }}</td>
            <td>{{ $request->user->name }}</td>
            <td>{{ $request->stream.' '.$request->year }}</td>
            <td><livewire:request.request-state :requestId="$request->id"></td>
            <td>{{ $request->message }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      </div>
      </div>
      </div>
               
    </div>
    