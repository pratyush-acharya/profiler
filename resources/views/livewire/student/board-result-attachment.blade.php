<div>
    @include('get-alert')
    <div class="row">
        <div class="container-fluid">
            <h6>Board Result Attachment List</h6>

            <table class="table csit-table">
                <thead>
                    <tr>
                        <th scope="col">S.N.</th>
                        <th scope="col">Student</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Board Result Attachment</th>
                        <th scope="col">Back Paper Attachments</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                
                <tbody>
                @forelse ($boardAttachments as $boardAttachment)
                    @if($boardAttachment->board_attachment != NULL)
                    <tr>
                        <th scope="row" >{{ $loop->iteration }}</th>
                        <td >{{ $boardAttachment->user->name }}</td>
                        <td>{{ $boardAttachment->semester }}</td>
                        <td>
                            <button type="button" class="btn" data-toggle="modal" data-target="#{{'boardResult'.$boardAttachment->id}}">
                            Board Result  <i class="fas fa-eye"></i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="{{'boardResult'.$boardAttachment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{'Board Result of Sem '.$boardAttachment->semester}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe src="{{ route('get-board-pdf',$boardAttachment->id) }}" width="100%" height="700px"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                        @forelse($boardAttachment->reBoardResult as $i=>$reBoardAttachment)
                        Re-Exam {{$i+1}} <br>

                        <!-- Modal -->
                            <div class="modal  fade" id="{{'reExamResult'.$reBoardAttachment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Re-Exam {{$i+1}} of Sem {{$boardAttachment->semester}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe src="{{ route('get-reboard-pdf',$reBoardAttachment->id) }}" width="100%" height="700px"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        <td>--</td>
                        @endforelse
                        </td>

                        <td>
                        @forelse($boardAttachment->reBoardResult as $reBoardAttachment)
                            <button type="button" class="btn" data-toggle="modal" data-target="#{{'reExamResult'.$reBoardAttachment->id}}">
                                <i class="fas fa-eye"></i>
                            </button>
                            |
                            <form style="display: inline-block">
                                <a href="#" onclick="confirm('Confirm Action?') || event.stopImmediatePropagation()" wire:click="deleteReBoardAttachment({{ $reBoardAttachment->id }})"><i class="far fa-trash-alt"></i></a>
                            </form>
                            <br>
                        @empty
                        <td></td>
                        @endforelse
                        </td>

                    </tr>
                @endif
                @empty                
                    <tr>
                        <th scope="row" colspan=3 style="text-align:left;">No Records Found!!!</th>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        
    </div>
</div>
