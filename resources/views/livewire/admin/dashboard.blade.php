<div>
@include('get-alert')
@if (session()->has('loginSuccess'))
    <div class="alert alert-success" role="alert">
        {{ session('loginSuccess') }}
    </div>
@endif

<!--Blue, Green and Orange labels-->
    <div class="row">
        <div class="col-md-3 ind-dash-stat1 mx-auto">
        <div class="numbers">
            <img src="/images/students-white.svg" class="img-icon">
            <h1>{{ $enrolled }}</h1>
            <p>ENROLLED STUDENTS</p>
        </div>
        </div>

        <div class="col-md-3 ind-dash-stat2 mx-auto">
        <div class="numbers">
            <img src="/images/mortarboard.svg" class="img-icon">
            <h1>{{ $batches }}</h1>
            <p>BATCHES</p>
        </div>
        </div>

        <div class="col-md-3 ind-dash-stat3 mx-auto">
        <div class="numbers">
            <img src="/images/mortarboard.svg" class="img-icon">
            <h1>{{ $graduates }}</h1>
            <p>GRADUATED</p>
        </div>
        </div>
    </div>
    <!--Requests and Log list-->
    <div class="row">
    <!--Request table-->
        <div class="col-md-8">
            <livewire:request.recent-request-list>
        </div>
        
        <!--Log List-->
        <div class="col-md-4">
            <livewire:log.recent-log-list>
        </div>
    </div>

    <!--User table and batch-->
    <div class="row">
    <!--User table-->
        <div class="col-md-8">
            <livewire:user.list-admin>
        </div>
    <!--              Batch table-->
        <div class="col-md-4">
    <!--                  CSIT Table-->
        <livewire:batch.list-csit>    

    <!--                  BCA Table-->
        <livewire:batch.list-bca>
        </div>
    </div>
</div>
