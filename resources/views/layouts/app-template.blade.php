<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('style/assets/img/logo.png') }}">
    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style/plugins/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('style/plugins/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/plugins/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/keyframes.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('style')
</head>
<body class="font-plus-jakarta-sans">
    @yield('loader')
    @include('components.preloader.loader')
    @yield('modal')
    <div class="flex w-full h-screen">
        <div class="layout-sidebar">
            @include('layouts.new.sidebar')
        </div>
        <div class="layout-pages w-full overflow-y-auto h-screen relative" id="scroll-body">
            @include('layouts.new.header')
            <div class="pages">
                @yield('content')
                @include('sweetalert::alert')
            </div>
            <div class="p-5 inset-x-0 bottom-0">
                <footer class="bg-white rounded-lg shadow sm:flex sm:items-center sm:justify-center p-4 sm:p-6 xl:p-8">
                    <p class="mb-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:mb-0">
                        &copy; {{ date('Y') }} BANK UMKM JATIM. All rights reserved.
                    </p>
                </footer>
            </div>
        </div>

    </div>

</body>
{{-- javascript plugins --}}
<script src="{{ asset('style/assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('style/plugins/js/select2.min.js') }}"></script>
<script src="{{ asset('resources/js/app.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
<script>
    var url = window.location;

    function formatNumber(number, precision = 0) {
        const numberParts = Number(Math.abs(number)).toFixed(precision).split('.');
        const integerPart = numberParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        const decimalPart = numberParts[1] || '0';

        if (precision == 0) {
            return `${number < 0 ? '-' : ''}${integerPart}`;
        }
        else {
            return `${number < 0 ? '-' : ''}${integerPart},${decimalPart}`;
        }
    }

    function formatRupiahExcel(number, precision = 0, formatted = true) {
        // Format the number using toLocaleString with the specified precision and 'id-ID' as the locale
        const numberFormatted = formatNumber(number, precision);

        // Check if the number is negative
        if (isNaN(number)) {
            return '-';
        }
        else {
            if (number < 0) {
                return formatted ? `(${numberFormatted.slice(1)})` : number;
            } else if (number === 0) {
                return '-';
            } else {
                return formatted ? numberFormatted : number;
            }
        }
    }

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
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
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
        $(".preloader").fadeOut("slow");
    });

    String.prototype.ucwords = function() {
        str = this.toLowerCase();
        return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function(s){
            return s.toUpperCase();
        });
    };

    function generateCsrfToken() {
        var token = "{{csrf_token()}}"
        if (token == '') {
            generateCsrfToken();
        }
        else {
            return token;
        }
    }

    // Limit Upload Slik
    $('.limit-size-10').on('change', function() {
        var size = (this.files[0].size / 1024 / 1024).toFixed(2)
        if (size > 10) {
            $(this).parent().next().html('Maksimal besar berkas adalah 10 MB')
            $(this).parent().next().css({
                "display": "block"
            });
            this.value = ''
        } else {
            $(this).parent().next().css({
                "display": "none"
            });
        }
    })
    // End Limit Upload

    // Only Accept file validation
    $(".only-image").on('change', function() {
        if (!this.files[0].type.includes('image')) {
            $(this).val('')
            $(this).parent().next().html('Hanya boleh memilih berkas berupa gambar(.jpg, .jpeg, .png, .webp)')
            $(this).parent().next().css({
                "display": "block"
            });
        }
        else {
            $(this).parent().next().css({
                "display": "none"
            });
        }
    })

    $(".only-pdf").on('change', function() {
        if (!this.files[0].type.includes('pdf')) {
            $(this).val('')
            $(this).parent().next().html('Hanya boleh memilih berkas berupa pdf.')
            $(this).parent().next().css({
                "display": "block"
            });
        }
        else {
            $(this).parent().next().css({
                "display": "none"
            });
        }
    })

    $(".image-pdf").on('change', function() {
        if (!this.files[0].type.includes('image') && !this.files[0].type.includes('pdf')) {
            $(this).val('')
            $(this).next().html('Hanya boleh memilih berkas berupa pdf dan gambar(.jpg, .jpeg, .png, .webp)')
            $(this).next().css({
                "display": "block"
            });
        }
        else {
            $(this).next().css({
                "display": "none"
            });
        }
    })
    // END Only Accept file validation
</script>
    @stack('extraScript')
    @stack('script')
    @yield('custom_script')
</html>
