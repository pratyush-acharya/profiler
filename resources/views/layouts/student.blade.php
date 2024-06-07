<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - Profiler</title>

    <!-- Bootstrap core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/progresscircle.css" rel="stylesheet">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    @livewireStyles
</head>

<body>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <img src="{{ Auth::user()->student->image }}" class="user-img">
                <div class="name-title">
                    <h6>{{ Auth::user()->name }}</h6>
                    <p class="user-admin-txt">Student</p>
                </div>
            </div><br>
            <div class="list-group list-group-flush">
                <a href="/student/" class="list-group-item list-group-item-action bg-light"><img src="/images/menu.svg"
                        width="30" height="30"> Dashboard</a>
                <a href="/request/create" class="list-group-item list-group-item-action bg-light"><img
                        src="/images/button.svg" width="30" height="30"> Create Request</a>
                <!-- <button type="button" class="btn pl-4 text-left" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus-circle fa-lg"></i> Upload CV</button> -->
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <button class="btn btn-primary toggle-menu" id="menu-toggle">Toggle Menu</button>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <button class="nav-link" type="button" name="logout" style="background:none; border:none;"
                                onclick='signOut()'>Logout <span class="sr-only">(current)</span></button>
                        </li>
                    </ul>
                </div>
            </nav>
            <!--      Page Content Start-->
            <div class="container-fluids profile-content">
                {{ $slot }}

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Upload CV</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="file" class="form-group">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/js/progresscircle.js"></script>
    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });

        $(function() {
            $('.circlechart').circlechart();
        });
    </script>

    @livewireScripts
    <script>
        window.livewire.on('showChart', message => {
            $(function() {
                $('.circlechart').circlechart();
            });
        })
    </script>

    <script>
        function signOut() {

            $.ajax({
                url: '{{ route('logout') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data == '200')
                        window.location.href = '/login';
                }
            });

        }
    </script>
</body>

</html>
