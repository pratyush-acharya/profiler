<div>
    @include('get-alert')
    <div class="row">
        <div class="container-fluid">
            <h6>Attachment List</h6>

            <table class="table csit-table">
                <thead>
                    <tr>
                        <th scope="col">S.N.</th>
                        <th scope="col">Category</th>
                        <th scope="col">Link</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                
                <tbody>
                @if($attachmentList != NULL && $attachmentList->count() > 0)
                @foreach ($attachmentList as $attachment)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $attachment->attachmentCategory->category_name }}</td>
                    <td><a href="#" wire:click.prevent="downloadAttachment({{$attachment->id}})"><i class="fas fa-paperclip"></i></a></td>
                    <td>
                        <form style="display: inline-block">
                            <a href="#" onclick="confirm('Confirm Action?') || event.stopImmediatePropagation()" wire:click="deleteAttachment({{ $attachment->id }})"><i class="far fa-trash-alt"></i></a>
                        </form>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <th scope="row" colspan=3 style="text-align:left;">No Records Found!!!</th>
                </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
