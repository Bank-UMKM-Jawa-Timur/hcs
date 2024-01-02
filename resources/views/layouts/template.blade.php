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
        Human Capital System | BANK UMKM JATIM
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
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
    {{-- Set Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

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

        .vh-100 {
            height: 90vh !important;
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
                <a href="" class="simple-text logo-normal font-weight-bold" style="font-size: 13px;">
                    Human Capital System
                </a>
            </div>

            <div class="row row-offcanvas row-offcanvas-left vh-100" style="width: 1700px">
                <div class="col-md-3 col-lg-2 sidebar-offcanvas h-100 overflow-auto bg-light pl-0" id="sidebar"
                    role="navigation">
                    @include('components.old.sidebar')
                </div>
            </div>

        </div>
        <div class="main-panel">
            @include('components.old.navbar')
            <div class="content">
                <div class="card">
                    @yield('content')
                    @include('sweetalert::alert')
                </div>
            </div>
            {{-- End Content --}}
            <footer class="footer footer-black border-top footer-white ">
                <div class="container-fluid">
                    <div class="row">
                        <div class="credits ml-auto">
                            <span class="copyright">
                                BANK UMKM JATIM ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @include('components.old.loader')

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
    {{-- <script src="{{ asset('style/assets/js/paper-dashboard.min.js') }}" type="text/javascript"></script> --}}
    <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    <script src="{{ asset('style/assets/demo/demo.js') }}"></script>
    <!-- Jam Realtime -->
    <script src="{{ asset('style/assets/js/jam.js') }}" async></script>
    <script src="{{ asset('style/assets/js/Datatables.js') }}"></script>
    <script src="{{ asset('style/assets/js/ReorderWithResize.js') }}"></script>
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('vendor/apexchart/apexcharts.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.0/moment.min.js"></script>

    <script>
        // $(document).ready(function() {
        //     // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
        //     demo.initChartsPages();
        // });
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

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function formatRupiahKoma(angka, prefix) {
            var number_string = angka.replace(/[^.\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan koma jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
        }

        $(window).on("load", function() {
            $(".loader-wrapper").fadeOut("slow");
        });
    </script>
    @yield('custom_script')
    @stack('script')
</body>

</html>
