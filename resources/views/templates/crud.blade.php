<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Spotifyn't</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-user"></i> Administrador
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard') }}">
                                Examén
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('students') }}">
                                Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('teachers') }}">
                                Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('groups') }}">
                                Groups
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('artistas') }}">
                                Artistas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('albumes') }}">
                                Albumes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('canciones') }}">
                                Canciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('canciones_reproductor') }}">
                                Reproductor
                            </a>
                        </li> 
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                @yield('body')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @yield('js')
</body>
</html>
