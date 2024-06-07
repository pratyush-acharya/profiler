<div>
<div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">
    <form wire:submit.prevent="store" enctype="multipart/form-data">
        @csrf        
        <div class="form-group px-4">
            <label for="message">Request Message</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" wire:model.lazy="message" id="message" placeholder="Type your message here.">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Submit</button>
                </div>
            </div>
            @error('studentSemester')<label class="text-danger">{{$message}}</label>@enderror
        </div>

        @error('message'){{$message}}@enderror
        <br>
    </form> 

    </div>
    </div>
    </div>

    <div class="row">
        <div class="col-10 mx-auto">
            <div class="container-fluid form-container">

            <h4 class="table-title">Request List</h4>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">S.N</th>
            <th scope="col">Message</th>
            <th scope="col">State</th>
            <th scope="col">Create At</th>
            <th scope="col">Updated At</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($requests as $request)
          <tr>
            <td  scope="row">{{ $loop->iteration }}</td>
            <td>{{ $request->message }}</td>
            <td><button class="btn btn-primary {{ $request->state }}" style="min-width: 120px;">{{ $request->state }}</button></td>
            <td>{{ date('Y-m-d', strtotime($request->created_at)) }}</td>
            <td>{{ date('Y-m-d', strtotime($request->updated_at)) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      </div>
      </div>
      <style>
        .solved{
            background-color: green;
            border-color: green; 
        }

        .ongoing{
            background-color: #ffc107;
            border-color: #ffc107; 
        }

        .rejected{
            background-color: #dc3545;
            border-color: #dc3545; 
        }

        .requested{
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
      </style>
      </div>
    
</div>
