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

    .vh-100 {
      height: 90vh!important;
    }
  </style>
  @stack('style')

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

      <div class="row row-offcanvas row-offcanvas-left vh-100" style="width: 1700px">
        <div class="col-md-3 col-lg-2 sidebar-offcanvas h-100 overflow-auto bg-light pl-0" id="sidebar" role="navigation">
          <ul class="nav flex-column sticky-top pl-2 mt-0">
            <li class="@active('home')">
                <a href="{{ route('home') }}" style="font-weight: bolder">
                    <i class="nc-icon nc-bank" style="font-weight: bolder"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            {{-- Menu Manajemen Karyawan --}}
            <li
                class="@active('karyawan,pengkinian_data,klasifikasi,mutasi,demosi,promosi,penonaktifan,import,pejabat-sementara.index,pejabat-sementara.create,pejabat-sementara.edit,surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit,reminder-pensiun.index,reminder-pensiun.show')"
            >
                <a class="nav-link" href="#submenu1" data-toggle="collapse" data-target="#submenu1" style="font-weight: bolder">
                  <i class="nc-icon nc-tile-56" style="font-weight: bolder"></i>
                  Manajemen Karyawan
                </a>
                <ul
                    class="sub-menu list-unstyled flex-column collapse pl-2
                    @active('karyawan,klasifikasi,mutasi,demosi,promosi,penonaktifan,import,pejabat-sementara.index,pejabat-sementara.create,pejabat-sementara.edit,surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit', 'show')"
                    id="submenu1"
                >
                  <li style="margin-top: -15px" class="@active('karyawan.index,karyawan.create,karyawan.edit,karyawan.show,import,klasifikasi')">
                    <a href="{{ route('karyawan.index') }}">
                      <i class="nc-icon nc-badge"></i>
                      <p>Karyawan </p>
                      <p></p>
                    </a>
                  </li>
                  <li style="margin-top: -15px" class="@active('reminder-pensiun.index,reminder-pensiun.show')">
                    <a href="{{ route('reminder-pensiun.index') }}">
                      <i class="nc-icon nc-badge"></i>
                      <p>Data Masa Pensiun</p>
                      <p></p>
                    </a>
                  </li>
                  <li style="margin-top: -15px" class="@active('pengkinian_data.index,pengkinian_data.create,pengkinian_data.edit,pengkinian_data.show,import')">
                    <a href="{{ route('pengkinian_data.index') }}">
                      <i class="nc-icon nc-ruler-pencil"></i>
                      <p>Pengkinian Data </p>
                      <p></p>
                    </a>
                  </li>
                  <li class="dropdown @active('mutasi,demosi,promosi,penonaktifan')" style="margin-top: -15px">
                      <a data-toggle="dropdown" aria-expanded="false">
                          <i class="nc-icon nc-chart-bar-32"></i>
                          <p class="dropdown-toggle" id="navbarDropdownMenuLink">Pergerakan Karir </p>
                          <p></p>
                      </a>
                      <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                          <a class="dropdown-item @active('mutasi.index')" href="{{ route('mutasi.index') }}">Mutasi</a>
                          <a class="dropdown-item @active('demosi.index')" href="{{ route('demosi.index') }}">Demosi</a>
                          <a class="dropdown-item @active('promosi.index')" href="{{ route('promosi.index') }}">Promosi</a>
                          <a class="dropdown-item @active('karyawan.penonaktifan')" href="{{ route('penonaktifan.index') }}">Penonaktifan</a>
                      </div>
                  </li>
                  <li style="margin-top: -15px" class="@active('pejabat-sementara.index,pejabat-sementara.create,pejabat-sementara.edit')">
                    <a href="{{ route('pejabat-sementara.index') }}">
                      <i class="nc-icon nc-tie-bow"></i>
                      <p>Penjabat Sementara</p>
                      <p></p>
                    </a>
                  </li>
                  <li class="dropdown @active('surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit')" style="margin-top: -15px">
                    <a data-toggle="dropdown" aria-expanded="false">
                        <i class="nc-icon nc-bell-55"></i>
                        <p class="dropdown-toggle" id="navbarDropdownMenuLink">Reward & Punishment </p>
                        <p></p>
                    </a>
                    <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                        <a class="dropdown-item @active('surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit')" href="{{ route('surat-peringatan.index') }}">Surat Peringatan</a>
                    </div>
                  </li>
                </ul>
            </li>
            {{-- Menu Penghasilan  --}}
            <li class="@active('gaji_perbulan,pajak_penghasilan')">
              <a class="nav-link" href="#submenu2" data-toggle="collapse" data-target="#submenu2" style="font-weight: bolder">
                <i class="nc-icon nc-tag-content" style="font-weight: bolder"></i>
                Penghasilan
              </a>
              <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('gaji_perbulan,pajak_penghasilan', 'show')" id="submenu2">
                <li style="margin-top: -15px" class="@active('gaji_perbulan')">
                  <a href="{{ route('gaji_perbulan.index') }}">
                    <i class="nc-icon nc-money-coins"></i>
                    <p>Proses Penghasilan</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('pajak_penghasilan')">
                  <a href="{{ route('pajak_penghasilan.index') }}">
                    <i class="nc-icon nc-scissors"></i>
                    <p>Pajak Penghasilan</p>
                    <p></p>
                  </a>
                </li>
              </ul>
            </li>
            {{-- Menu Histori --}}
            <li class="@active('history')">
              <a class="nav-link" href="#submenu3" data-toggle="collapse" data-target="#submenu3" style="font-weight: bolder">
                <i class="nc-icon nc-compass-05" style="font-weight: bolder"></i>
                Histori
              </a>
              <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('history', 'show')" id="submenu3">
                <li style="margin-top: -15px" class="@active('history_jabatan')">
                  <a href="{{ route('history_jabatan.index') }}">
                    <i class="nc-icon nc-briefcase-24"></i>
                    <p>Jabatan</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('pejabat-sementara.history')">
                  <a href="{{ route('pejabat-sementara.history') }}">
                    <i class="nc-icon nc-tie-bow"></i>
                    <p>Penjabat Sementara</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('surat-peringatan.history')">
                  <a href="{{ route('surat-peringatan.history') }}?tahun={{ date('Y') }}">
                    <i class="nc-icon nc-email-85"></i>
                    <p>Surat Peringatan</p>
                    <p></p>
                  </a>
                </li>
              </ul>
            </li>
            {{-- Menu Laporan --}}
            <li class="@active('laporan,index_dpp')">
              <a class="nav-link" href="#submenu4" data-toggle="collapse" data-target="#submenu4" style="font-weight: bolder">
                <i class="nc-icon nc-paper" style="font-weight: bolder"></i>
                Laporan
              </a>
              <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('laporan,index_dpp', 'show')" id="submenu4">
                <li class="dropdown" style="margin-top: -15px">
                  <a data-toggle="dropdown" aria-expanded="false">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p class="dropdown-toggle" id="navbarDropdownMenuLink">Laporan Pergerakan Karir </p>
                    <p></p>
                  </a>
                  <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                      <a class="dropdown-item" href="#">Laporan Mutasi</a>
                      <a class="dropdown-item" href="#">Laporan Demosi</a>
                      <a class="dropdown-item" href="#">Laporan Promosi</a>
                      <a class="dropdown-item" href="#">Laporan Penonaktifan</a>
                  </div>
                </li>
                <li style="margin-top: -15px" class="@active('laporan_jamsostek')">
                  <a href="{{ route('laporan_jamsostek.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>Laporan Jamsostek</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('index_dpp')">
                  <a href="{{ route('index_dpp') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>Laporan DPP</p>
                    <p></p>
                  </a>
                </li>
              </ul>
            </li>
            {{-- Menu Gaji --}}
            <li class="@active('gaji, slipIndex')">
              <a class="nav-link" href="#submenu5" data-toggle="collapse" data-target="#submenu5" style="font-weight: bolder">
                <i class="nc-icon nc-credit-card" style="font-weight: bolder"></i>
                Gaji
              </a>
              <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('gaji, slipIndex', 'show')" id="submenu5">
                <li style="margin-top: -15px" class="@active('gaji')">
                  <a href="{{ route('gaji.index') }}">
                    <i class="nc-icon nc-money-coins"></i>
                    <p>Lampiran Gaji</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('slipIndex')">
                  <a href="{{ route('slipIndex') }}">
                    <i class="nc-icon nc-money-coins"></i>
                    <p>Slip Jurnal</p>
                    <p></p>
                  </a>
                </li>
              </ul>
            </li>
            {{-- Menu Migrasi Data --}}
            <li class="@active('migrasi')">
              <a class="nav-link" href="#submenu8" data-toggle="collapse" data-target="#submenu8" style="font-weight: bolder">
                <i class="nc-icon nc-cloud-upload-94" style="font-weight: bolder"></i>
                Migrasi
              </a>
              <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('migrasi')" id="submenu8">
                <li style="margin-top: -15px" class="@active('migrasiJabatan')">
                  <a href="{{ route('migrasiJabatan') }}">
                    <i class="nc-icon nc-cloud-upload-94"></i>
                    <p>Jabatan</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('migrasiPJS')">
                  <a href="{{ route('migrasiPJS') }}">
                    <i class="nc-icon nc-cloud-upload-94"></i>
                    <p>Penjabat Sementara</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('migrasiSP')">
                  <a href="{{ route('migrasiSP') }}">
                    <i class="nc-icon nc-cloud-upload-94"></i>
                    <p>Surat Peringatan</p>
                    <p></p>
                  </a>
                </li>
              </ul>
            </li>
            {{-- Menu Log Aktivitas --}}
            <li >
              <a class="nav-link" href="#submenu5" data-toggle="collapse" data-target="#submenu6" style="font-weight: bolder">
                <i class="nc-icon nc-tap-01" style="font-weight: bolder"></i>
                Log
              </a>
              <ul class="sub-menu list-unstyled flex-column collapse pl-2" id="submenu6" aria-expanded="false">
                <li style="margin-top: -15px">
                  <a href="#">
                    <i class="nc-icon nc-refresh-69"></i>
                    <p>Log Aktivitas</p>
                    <p></p>
                  </a>
                </li>
              </ul>
            </li>
            {{-- Menu Setting --}}
            <li class="@active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database')">
              <a class="nav-link" href="#submenu6" data-toggle="collapse" data-target="#submenu7" style="font-weight: bolder">
                <i class="nc-icon nc-settings" style="font-weight: bolder"></i>
                Setting
              </a>
              <ul
                class="sub-menu list-unstyled flex-column collapse pl-2 @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database', 'show')"
                id="submenu7">
                <li class="dropdown @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur', 'show')" style="margin-top: -15px">
                    <a data-toggle="dropdown" aria-expanded="false">
                        <i class="nc-icon nc-box"></i>
                        <p class="dropdown-toggle" id="navbarDropdownMenuLink">Master </p>
                        <p></p>
                    </a>
                    <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                        <a class="dropdown-item @active('cabang.index')" href="{{ route('cabang.index') }}">Kantor Cabang</a>
                        <a class="dropdown-item @active('divisi.index')" href="{{ route('divisi.index') }}">Divisi</a>
                        <a class="dropdown-item @active('sub_divisi.index')" href="{{ route('sub_divisi.index') }}">Sub Divisi</a>
                        <a class="dropdown-item @active('bagian.index')" href="{{ route('bagian.index') }}">Bagian</a>
                        <a class="dropdown-item @active('jabatan.index')" href="{{ route('jabatan.index') }}">Jabatan</a>
                        <a class="dropdown-item @active('pangkat_golongan.index')" href="{{ route('pangkat_golongan.index') }}">Pangkat & Golongan</a>
                        <a class="dropdown-item @active('tunjangan.index')" href="{{ route('tunjangan.index') }}">Tunjangan</a>
                        <a class="dropdown-item @active('umur.index')" href="{{ route('umur.index') }}">Rentang Umur</a>
                    </div>
                </li>
                <li style="margin-top: -15px">
                  <a href="#">
                    <i class="nc-icon nc-single-02"></i>
                    <p>User Akses</p>
                    <p></p>
                  </a>
                </li>
                <li style="margin-top: -15px" class="@active('database')">
                  <a href="{{ route('database.index') }}">
                    <i class="nc-icon nc-vector"></i>
                    <p>Database</p>
                    <p></p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
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
                BANK UMKM JATIM © <script>
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
  <script src="{{ asset('style/assets/js/core/jquery.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
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

    var url = window.location;

    // for sidebar menu entirely but not cover treeview
    // $('ul.nav>li>a').filter(function() {
    //   return this.href == url;
    // }).parent().addClass('active');

    // // for treeview
    // $('ul.sub-menu>li>a').filter(function() {
    //   return this.href == url;
    // }).parentsUntil(".nav > .sub-menu").addClass('active show');

    // $('ul.sub-menu>li.dropdown>div.dropdown-menu>a').filter(function() {
    //   return this.href == url;
    // }).parentsUntil(".nav > .sub-menu").addClass('active');

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
  @stack('script')
</body>

</html>
