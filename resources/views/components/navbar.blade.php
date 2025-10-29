<nav class="navbar navbar-expand-lg nav-custom">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="{{route('home')}}">Fantacalcio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" aria-current="page" href="{{route('home')}}">Home</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('leagues.joinForm') }}">Unisciti a una lega</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('leagues.index') }}">Le mie leghe</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Ciao {{Auth::user()->name}}</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">Logout</a></li>
                        <form action="{{route('logout')}}" method="POST" class="d-none" id="form-logout">@csrf</form>
                    </ul>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Ciao</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('login')}}">Accedi</a></li>
                        <hr class="dropdown-divider">
                        <li><a class="dropdown-item" href="{{route('register')}}">Registrati</a></li>
                    </ul>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>