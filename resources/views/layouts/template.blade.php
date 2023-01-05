<!--
=========================================================
* Paper Dashboard 2 - v2.0.1
=========================================================

* Product Page: https://www.creative-tim.com/product/paper-dashboard-2
* Copyright 2020 Creative Tim (https://www.creative-tim.com)

Coded by www.creative-tim.com

 =========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('style/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('style/assets/img/logo.png') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Bio Interface | BANK UMKM JATIM
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <!-- CSS Files -->
  <link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/css/datatables.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('style/assets/css/loading.css') }}">
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">

  <style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }

    .loader-wrapper {
      width: 100%;
      height: 100%;
      top: 0;
      left: 100px;
      position: fixed;
      background-color: rgba(110, 110, 110, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>

</head>

<body>
  <div class="wrapper">
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="m-sm-2 logo">
        <a href="" class="simple-text logo-mini">
          <div class="logo-image-small">
            <img src="{{ asset('style/assets/img/logo.png') }}">
          </div>
        </a>
        <a href="" class="simple-text logo-normal">
          Bio Interface
        </a>
      </div>
      <div class="dropdown sidebar-wrapper">
        <ul class="nav">
            <li class="active">
                <a href="/home">
                    <i class="nc-icon nc-bank"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li>
                <a class="nav-link disabled">
                    <p>Navigation</p>
                </a>
            </li>
            <li class="dropdown">
                <a href="" data-toggle="dropdown" aria-expanded="false">
                    <i class="nc-icon nc-badge"></i>
                    <p class="dropdown-toggle" id="navbarDropdownMenuLink">Karyawan </p>
                    <p></p>
                </a>
                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('karyawan.index') }}">Data Karyawan</a>
                    <a class="dropdown-item" href="{{ route('mutasi.index') }}">Mutasi</a>
                    <a class="dropdown-item" href="{{ route('demosi.index') }}">Demosi</a>
                    <a class="dropdown-item" href="{{ route('promosi.index') }}">Promosi</a>
                    {{-- <a class="dropdown-item" href="{{ route('tunjangan_karyawan.index') }}">Tunjangan Karyawan</a> --}}
                </div>
            </li>
            <li class="dropdown">
                <a href="" data-toggle="dropdown" aria-expanded="false">
                    <i class="nc-icon nc-briefcase-24"></i>
                    <p class="dropdown-toggle" id="navbarDropdownMenuLink">Master Data </p>
                    <p></p>
                </a>
                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('cabang.index') }}">Kantor Cabang</a>
                    <a class="dropdown-item" href="{{ route('divisi.index') }}">Divisi</a>
                    <a class="dropdown-item" href="{{ route('sub_divisi.index') }}">Sub Divisi</a>
                    <a class="dropdown-item" href="{{ route('jabatan.index') }}">Jabatan</a>
                    <a class="dropdown-item" href="{{ route('pangkat_golongan.index') }}">Pangkat & Golongan</a>
                    <a class="dropdown-item" href="{{ route('tunjangan.index') }}">Tunjangan</a>
                    <a class="dropdown-item" href="{{ route('bagian.index') }}">Bagian</a>
                </div>
            </li>
            <li class="dropdown">
              <a href="" data-toggle="dropdown" aria-expanded="false">
                  <i class="nc-icon nc-money-coins"></i>
                  <p class="dropdown-toggle" id="navbarDropdownMenuLink">Gaji </p>
                  <p></p>
              </a>
              <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                  <a class="dropdown-item" href="{{ route('gaji_perbulan.index') }}">Pembayaran Gaji</a>
                  <a class="dropdown-item" href="/penghasilan">Pajak Penghasilan</a>
              </div>
          </li>
            <li class="dropdown">
                <a href="" data-toggle="dropdown" aria-expanded="false">
                  <i class="nc-icon nc-paper"></i>
                  <p class="dropdown-toggle" id="navbarDropdownMenuLink">Laporan </p>
                  <p></p>
                </a>
                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                    <a class="dropdown-item" href="#">Laporan Mutasi</a>
                    <a class="dropdown-item" href="#">Laporan Demosi</a>
                    <a class="dropdown-item" href="#">Laporan Promosi</a>
                    <a class="dropdown-item" href="{{ route('laporan_jamsostek.index') }}">Laporan Jamsostek</a>
                    <a class="dropdown-item" href="{{ route('index_dpp') }}">Laporan DPP</a>
                </div>
            </li>
            <li class="active-pro">
                <a href="">
                <p class="text-center">BANK UMKM JATIM</p>
                </a>
            </li>
        </ul>
      </div>

    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar fixed-top navbar-expand-lg navbar-absolute navbar-transparent">
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
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="card m-sm-1  navbar-nav">
                <li class="nav-item btn-rotate">
                    <a class="nav-link noHover">
                      <i class="nc-icon nc-watch-time"></i>
                      <p id="DisplayClock" class="" onload="showTime()"></p>
                    </a>
                </li>
              </ul>
            <ul class="card m-sm-1 navbar-nav">
              <li class="nav-item btn-rotate dropdown">
                  <a class="nav nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="nc-icon nc-single-02"></i>
                    {{-- @if (session('status')) --}}
                      <p>Halo, {{ auth()->user()->name }}</p>
                      <p></p>
                    {{-- @endif --}}
                  </a>
                <div class="dropdown-menu dropdown-primary dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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

      <div class="content">

        <div class="card">
          @yield('content')
          @include('sweetalert::alert')
        </div>

      </div>
      {{-- End Content --}}
      <footer class="footer footer-black  footer-white ">
        <div class="container-fluid">
          <div class="row">
            <div class="credits ml-auto">
              <span class="copyright">
                © <script>
                  document.write(new Date().getFullYear())
                </script>
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <div class="loader-wrapper">
    <div class="la-line-spin-clockwise-fade la-dark la-2x">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="resources/js/dselect.js"></script>
  <script src="{{ asset('style/assets/js/core/jquery.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="{{ asset('style/assets/js/plugins/chartjs.min.js') }}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{ asset('style/assets/js/plugins/bootstrap-notify.js') }}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('style/assets/js/paper-dashboard.min.js') }}" type="text/javascript"></script>
  <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{ asset('style/assets/demo/demo.js') }}"></script>
  <!-- Jam Realtime -->
  <script src="{{ asset('style/assets/js/jam.js') }}" async></script>
  <script src="{{ asset('style/assets/js/Datatables.js') }}"></script>
  <script src="{{ asset('style/assets/js/ReorderWithResize.js') }}"></script>
  <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      demo.initChartsPages();
    });
    function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

    $(window).on("load", function() {
      $(".loader-wrapper").fadeOut("slow");
    });
  </script>
  @yield('custom_script')
</body>

</html>
