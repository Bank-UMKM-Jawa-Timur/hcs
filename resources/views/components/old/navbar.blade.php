
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-absolute navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-bar navbar-kebab"></span>
                <span class="navbar-toggler-bar navbar-kebab"></span>
                <span class="navbar-toggler-bar navbar-kebab"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navigation">
                <ul class="m-sm-1  navbar-nav">
                    <li class="nav-item btn-rotate">
                        <a class="nav-link noHover">
                            <i class="nc-icon nc-watch-time"></i>
                            <p id="DisplayClock" class="font-weight-bold" onload="showTime()"></p>
                        </a>
                    </li>
                </ul>
                <ul class=" m-sm-1 navbar-nav">
                    <li class="nav-item btn-rotate dropdown d-flex">
                        
                        <a class="nav nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <p class="font-weight-bold">Selamat datang,{{ Auth::guard('karyawan')->check() ? auth()->user()->nama_karyawan : auth()->user()->name }}</p>
                                <img class="ml-2 mr-2 " src="https://ui-avatars.com/api/?background=DA271F&color=FFFFFF&name={{ Auth::guard('karyawan')->check() ? auth()->user()->nama_karyawan : auth()->user()->name }}" style="width: 30px; border-radius: 100%;" alt="">
                        </a>
            
                        <div class="dropdown-menu dropdown-primary dropdown-menu-right"
                            aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('password.request') }}">
                                Ganti Password
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                class="d-none">
                                @csrf
                            </form>

                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    {{-- Content --}}