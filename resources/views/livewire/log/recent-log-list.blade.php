<div>
    <div class="container-fluid request-table">
        <h4 class="table-title">Log Activity</h4>
        <hr>
        <div class="container scroll-list">
        <ul class="record-list">
        @foreach($userlogs as $userlog)
            <li>
            <div class="req-record">
                <div class="img-details">
                <img src="/images/logo.png" class="user-img">
                <div class="name-title">
                    <h6>{{ $userlog->user->name }}</h6>
                    <p class="user-admin-txt">{{ $userlog->log_detail }} -- {{ $userlog->updated_at }}</p>
                </div>
                </div>
            </div>
            </li>
        @endforeach
        </ul>
        </div>
    </div>
</div>
