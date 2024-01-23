<div class="sidebar w-[280px] pb-32 bg-white h-screen overflow-y-auto lg:block hidden border-r">
    <div class="head p-5 border-b top-0 sticky bg-white z-20">
        <div class="logo-brand flex gap-3">
            <img src="{{ asset('style/assets/img/logo.png') }}" class="w-8" alt="">
            <h2 class="font-semibold mt-1 text-sm">Human Capital System</h2>
        </div>
    </div>
    <div class="menu mt-5 space-y-5 divide-y" id="accordion-flush" data-accordion="collapse" data-active-classes="active">
        <div class="menus">
            <div class="ml-5 category">
                <p class="text-xs font-semibold tracking-widest text-gray-400">MENU</p>
            </div>
            <ul class="sub-menu">
                @if (Auth::guard('karyawan')->check())
                    <li class="item-link">
                        <a href="{{ route('home') }}">
                            <button class="btn-link @active('home')">
                                <i class="ti ti-layout-dashboard"></i>
                                <span>Dashboard</span>
                            </button>
                        </a>
                    </li>
                @else
                    @can('dashboard')
                        <li class="item-link">
                            <a href="{{ route('home') }}">
                                <button class="btn-link @active('home')">
                                    <i class="ti ti-layout-dashboard"></i>
                                    <span>Dashboard</span>
                                </button>
                            </a>
                        </li>
                    @endcan
                @endif

                {{-- Menu Manajemen Karyawan --}}
                @can('manajemen karyawan')
                    <li class="item-link relative">
                        <a href="#" id="headingOne">
                            <button
                                class="btn-link {{ request()->is(
                                    'karyawan',
                                    'karyawan/*',
                                    'reminder_pensiun',
                                    'reminder_pensiun/*',
                                    'reminder_pensiun-show',
                                    'reminder_pensiun-show/*',
                                    'pengkinian_data',
                                    'pengkinian_data/*',
                                    'mutasi',
                                    'mutasi/*',
                                    'demosi',
                                    'demosi/*',
                                    'promosi',
                                    'promosi/*',
                                    'penonaktifan',
                                    'penonaktifan/*',
                                    'pejabat-sementara',
                                    'surat-peringatan',
                                )
                                    ? 'active'
                                    : '' }}"
                                type="button" data-accordion-target="#karyawan" aria-expanded="false"
                                aria-controls="karyawan">
                                <i class="ti ti-users"></i>
                                <span>Manajemen Karyawan</span>
                                <span class="absolute right-3 mt-[3px] text-sm">
                                    <iconify-icon icon="fa6-solid:angle-right" data-accordion-icon></iconify-icon>
                                </span>
                            </button>
                        </a>
                        <div id="karyawan"
                            class="accordion-menu mt-2 {{ request()->is(
                                'karyawan',
                                'karyawan/*',
                                'reminder_pensiun',
                                'reminder_pensiun/*',
                                'reminder_pensiun-show',
                                'reminder_pensiun-show/*',
                                'pengkinian_data',
                                'pengkinian_data/*',
                                'mutasi',
                                'mutasi/*',
                                'demosi',
                                'demosi/*',
                                'promosi',
                                'promosi/*',
                                'penonaktifan',
                                'penonaktifan/*',
                                'pejabat-sementara',
                                'surat-peringatan',
                            )
                                ? 'show'
                                : 'hidden' }}"
                            id="karyawan" aria-labelledby="headingOne">
                            <ul class="sub-menu" data-accordion="collapse" data-active-classes="active-link">
                                @can('manajemen karyawan - data karyawan')
                                    <li class="item-link">
                                        <a href="{{ route('karyawan.index') }}">
                                            <button
                                                class="btn-link {{ request()->is('karyawan', 'karyawan/*') ? 'active-link' : '' }}">
                                                <i class="ti ti-circle"></i>
                                                <span>Karyawan</span>
                                            </button>
                                        </a>
                                    </li>
                                @endcan
                                @can('manajemen karyawan - data masa pensiunan')
                                    <li class="item-link">
                                        <a href="{{ route('reminder-pensiun.index') }}">
                                            <button
                                                class="btn-link {{ request()->is('reminder_pensiun', 'reminder_pensiun/*', 'reminder_pensiun-show', 'reminder_pensiun-show/*')
                                                    ? 'active-link'
                                                    : '' }}">
                                                <i class="ti ti-circle"></i>
                                                <span>Data Masa Pensiun</span>
                                            </button>
                                        </a>
                                    </li>
                                @endcan
                                @can('manajemen karyawan - pengkinian data')
                                    <li class="item-link">
                                        <a href="{{ route('pengkinian_data.index') }}">
                                            <button
                                                class="btn-link {{ request()->is('pengkinian_data', 'pengkinian_data/*') ? 'active-link' : '' }}">
                                                <i class="ti ti-circle"></i>
                                                <span>Pengkinian Data</span>
                                            </button>
                                        </a>
                                    </li>
                                @endcan
                                @can('manajemen karyawan - pergerakan karir')
                                    <li class="item-link">
                                        <a href="#">
                                            <button class="btn-link" data-accordion-target="#pergerakan-karir"
                                                aria-expanded="false" aria-controls="pergerkan-karir">
                                                <i class="ti ti-circle"></i>
                                                <span>Pergerakan Karir</span>
                                                <span class="absolute right-3 mt-[3px] text-sm">
                                                    <iconify-icon icon="fa6-solid:angle-right" data-accordion-icon>
                                                    </iconify-icon>
                                                </span>
                                            </button>
                                        </a>
                                        <div class="accordion-menu border-l p-3 mt-2 hidden" id="pergerakan-karir"
                                            aria-labelledby="headingTwo">
                                            <ul class="sub-menu pl-2 ml-3 space-y-2 border-them border-l-2 text-sm">
                                                <li>
                                                    @can('manajemen karyawan - pergerakan karir - data mutasi')
                                                        <a href="{{ route('mutasi.index') }}">
                                                            <button class="btn-link @active('mutasi.index', 'active-link')">
                                                                <span>Mutasi</span>
                                                            </button>
                                                        </a>
                                                    @endcan
                                                </li>
                                                <li>
                                                    @can('manajemen karyawan - pergerakan karir - data demosi')
                                                        <a href="{{ route('demosi.index') }}">
                                                            <button class="btn-link @active('demosi.index', 'active-link')">
                                                                <span>Demosi</span>
                                                            </button>
                                                        </a>
                                                    @endcan
                                                </li>
                                                <li>
                                                    @can('manajemen karyawan - pergerakan karir - data promosi')
                                                        <a href="{{ route('promosi.index') }}">
                                                            <button class="btn-link @active('promosi.index', 'active-link')">
                                                                <span>Promosi</span>
                                                            </button>
                                                        </a>
                                                    @endcan
                                                </li>
                                                <li>
                                                    @can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan')
                                                        <a href="{{ route('penonaktifan.index') }}">
                                                            <button class="btn-link" @active('karyawan.penonaktifan', 'active-link')>
                                                                <span>Penonaktifan</span>
                                                            </button>
                                                        </a>
                                                    @endcan
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('manajemen karyawan - data penjabat sementara')
                                    <li class="item-link">
                                        <a href="{{ route('pejabat-sementara.index') }}">
                                            <button class="btn-link">
                                                <i class="ti ti-circle"></i>
                                                <span>Pejabat Sementara</span>
                                            </button>
                                        </a>
                                    </li>
                                    @endif
                                    @can('manajemen karyawan - reward & punishment')
                                        <li class="item-link">
                                            <a href="#">
                                                <button class="btn-link" data-accordion-target="#reward-and-punishment"
                                                    aria-expanded="false" aria-controls="reward-and-punishment">
                                                    <i class="ti ti-circle"></i>
                                                    <span>Reward & Punishment</span>
                                                    <span class="absolute right-3 mt-[3px] text-sm">
                                                        <iconify-icon icon="fa6-solid:angle-right" data-accordion-icon>
                                                        </iconify-icon>
                                                    </span>
                                                </button>
                                            </a>
                                            <div class="accordion-menu border-l p-3 mt-2 hidden" id="reward-and-punishment"
                                                aria-labelledby="headingTwo">
                                                <ul class="sub-menu pl-2 ml-3 space-y-2 border-them border-l-2 text-sm">
                                                    <li>
                                                        <a href="{{ route('surat-peringatan.index') }}">
                                                            <button class="btn-link">
                                                                <span>Surat Peringatan</span>
                                                            </button>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endcan
                        {{-- Penghasilan --}}
                        @if (Auth::guard("karyawan")->check())
                            <li class="item-link relative">
                                <a href="#">
                                    <button class="btn-link @active('pajak_penghasilan','penghasilan/get-gaji') {{ request()->is(
                                        'penghasilan-tidak-teratur',
                                        'penghasilan-tidak-teratur/*',
                                        'bonus',
                                        'bonus/*',
                                        'potongan',
                                        'potongan/*',
                                        'gaji_perbulan',
                                        'gaji_perbulan/*',
                                        'penghasilan/import-penghasilan-teratur',
                                        'penghasilan/import-penghasilan-teratur/*',
                                        'pengganti-biaya-kesehatan',
                                        'pengganti-biaya-kesehatan/*',
                                        'uang-duka',
                                        'uang-duka/*',
                                        'penghasilan/get-gaji',
                                        'penghasilan/get-gaji/*',
                                        'penghasilan/details',
                                        'penghasilan/details/*',
                                        'payroll',
                                        'payroll/*',
                                        'gaji',
                                        'gaji/*',
                                        'slip_jurnal',
                                        'slip_jurnal/*',
                                        'slip',
                                        'slip/*',
                                        'pajak_penghasilan',
                                        'pajak_penghasilan/*'
                                        ) ? 'active' : '' }}" data-accordion-target="#penghasilan" aria-expanded="false"
                                        aria-controls="penghasilan">
                                        <i class="ti ti-cash"></i>
                                        <span>Penghasilan</span>
                                        <span class="absolute right-3 mt-[3px] text-sm">
                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                        </span>
                                    </button>
                                </a>
                                <div id="penghasilan"
                                    class="accordion-menu {{ request()->is(
                                        'penghasilan-tidak-teratur',
                                        'penghasilan-tidak-teratur/*',
                                        'bonus',
                                        'bonus/*',
                                        'potongan',
                                        'potongan/*',
                                        'gaji_perbulan',
                                        'gaji_perbulan/*',
                                        'penghasilan/import-penghasilan-teratur',
                                        'penghasilan/import-penghasilan-teratur/*',
                                        'pengganti-biaya-kesehatan',
                                        'pengganti-biaya-kesehatan/*',
                                        'uang-duka',
                                        'uang-duka/*',
                                        'penghasilan/get-gaji',
                                        'penghasilan/get-gaji/*',
                                        'penghasilan/details',
                                        'penghasilan/details/*',
                                        'payroll',
                                        'payroll/*',
                                        'gaji',
                                        'gaji/*',
                                        'slip_jurnal',
                                        'slip_jurnal/*',
                                        'slip',
                                        'slip/*',
                                        'pajak_penghasilan',
                                        'pajak_penghasilan/*'
                                        ) ? 'show' : 'hidden' }}">
                                    <ul class="sub-menu" data-accordion="collapse" data-active-classes="active-link">
                                        <li class="item-link">
                                            <a href="#">
                                                <button class="btn-link" data-accordion-target="#gaji" aria-expanded="false"
                                                    aria-controls="gaji">
                                                    <i class="ti ti-circle"></i>
                                                    <span>Gaji</span>
                                                    <span class="absolute right-3 mt-[3px] text-sm">
                                                        <iconify-icon icon="fa6-solid:angle-right" data-accordion-icon>
                                                        </iconify-icon>
                                                    </span>
                                                </button>
                                            </a>
                                            <div class="accordion-menu border-l p-3 mt-2 @active('slipIndex', 'active-link') {{ request()->is('gaji', 'gaji/*') ? 'show' : 'hidden' }}"
                                                id="gaji" aria-labelledby="headingTwo">
                                                <ul class="sub-menu pl-2 ml-3 space-y-2 border-them border-l-2 text-sm">
                                                    @if(hasPermission('penghasilan - gaji - slip jurnal'))
                                                        <li>
                                                            <a href="{{ route('slipIndex') }}">
                                                                <button class="btn-link">
                                                                    <span>Slip jurnal</span>
                                                                </button>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(hasPermission('penghasilan - gaji - slip gaji'))
                                                        <li>
                                                            <a href="{{ route('slip.index') }}">
                                                                <button class="btn-link">
                                                                    <span>Slip Gaji</span>
                                                                </button>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        {{-- end Karyawan --}}
                        @can('penghasilan')
                            <li class="item-link relative">
                                <a href="#">
                                    <button class="btn-link @active('pajak_penghasilan','penghasilan/get-gaji') {{ request()->is(
                                        'penghasilan-tidak-teratur',
                                        'penghasilan-tidak-teratur/*',
                                        'edit-tunjangan-bonus/*',
                                        'penghasilan/edit-tunjangan',
                                        'bonus',
                                        'bonus/*',
                                        'potongan',
                                        'potongan/*',
                                        'gaji_perbulan',
                                        'gaji_perbulan/*',
                                        'penghasilan/import-penghasilan-teratur',
                                        'penghasilan/import-penghasilan-teratur/*',
                                        'pengganti-biaya-kesehatan',
                                        'pengganti-biaya-kesehatan/*',
                                        'uang-duka',
                                        'uang-duka/*',
                                        'penghasilan/get-gaji',
                                        'penghasilan/get-gaji/*',
                                        'penghasilan/details',
                                        'penghasilan/details/*',
                                        'payroll',
                                        'payroll/*',
                                        'gaji',
                                        'gaji/*',
                                        'slip_jurnal',
                                        'slip_jurnal/*',
                                        'slip',
                                        'slip/*',
                                        'pajak_penghasilan',
                                        'pajak_penghasilan/*'
                                        ) ? 'active' : '' }}" data-accordion-target="#penghasilan" aria-expanded="false"
                                        aria-controls="penghasilan">
                                        <i class="ti ti-cash"></i>
                                        <span>Penghasilan</span>
                                        <span class="absolute right-3 mt-[3px] text-sm">
                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                        </span>
                                    </button>
                                </a>
                                <div id="penghasilan"
                                    class="accordion-menu {{ request()->is(
                                        'penghasilan-tidak-teratur',
                                        'penghasilan-tidak-teratur/*',
                                        'edit-tunjangan-bonus',
                                        'edit-tunjangan-bonus/*',
                                        'penghasilan/edit-tunjangan',
                                        'bonus',
                                        'bonus/*',
                                        'potongan',
                                        'potongan/*',
                                        'gaji_perbulan',
                                        'gaji_perbulan/*',
                                        'penghasilan/import-penghasilan-teratur',
                                        'penghasilan/import-penghasilan-teratur/*',
                                        'pengganti-biaya-kesehatan',
                                        'pengganti-biaya-kesehatan/*',
                                        'uang-duka',
                                        'uang-duka/*',
                                        'penghasilan/get-gaji',
                                        'penghasilan/get-gaji/*',
                                        'penghasilan/details',
                                        'penghasilan/details/*',
                                        'payroll',
                                        'payroll/*',
                                        'gaji',
                                        'gaji/*',
                                        'slip_jurnal',
                                        'slip_jurnal/*',
                                        'slip',
                                        'slip/*',
                                        'pajak_penghasilan',
                                        'pajak_penghasilan/*'
                                        ) ? 'show' : 'hidden' }}">
                                    <ul class="sub-menu" data-accordion="collapse" data-active-classes="active-link">
                                        @can('penghasilan')
                                            <li class="item-link">
                                                <a href="{{ route('gaji_perbulan.index') }}">
                                                    <button class="btn-link @active('gaji_perbulan', 'active-link')">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Proses Penggajian</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('penghasilan - pajak penghasilan')
                                            <li class="item-link">
                                                <a href="{{ route('pajak_penghasilan.index') }}">
                                                    <button class="btn-link @active('pajak_penghasilan.index', 'active-link') @active('get-penghasilan', 'active-link')">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Pajak</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('penghasilan - import - penghasilan teratur')
                                            <li class="item-link">
                                                <a href="{{ route('penghasilan.import-penghasilan-teratur.index') }}">
                                                    <button class="btn-link @active('penghasilan.import-penghasilan-teratur.index', 'active-link') {{ request()->is('penghasilan/import-penghasilan-teratur/create','penghasilan/edit-tunjangan') ? 'active-link' : '' }}">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Penghasilan Teratur</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('penghasilan - import - penghasilan tidak teratur')
                                            <li class="item-link">
                                                <a href="{{ route('penghasilan-tidak-teratur.index') }}">
                                                    <button class="btn-link {{ request()->is('penghasilan-tidak-teratur/detail/*','penghasilan-tidak-teratur','penghasilan-tidak-teratur/edit-tunjangan/*','penghasilan-tidak-teratur/input-tidak-teratur') ? 'active-link' : '' }}">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Penghasilan Tidak Teratur</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('penghasilan - import - bonus')
                                            <li class="item-link">
                                                <a href="{{ route('bonus.index') }}">
                                                    <button class="btn-link {{ request()->is('bonus/import-data','edit-tunjangan-bonus/*') ? 'active-link' : '' }} @active('bonus.index', 'active-link')">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Bonus</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('penghasilan - payroll - list payroll')
                                            <li class="item-link">
                                                <a href="{{ route('payroll.index') }}">
                                                    <button class="btn-link @active('payroll.index', 'active-link')">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Payroll</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        <li class="item-link">
                                            <a href="#">
                                                <button class="btn-link" data-accordion-target="#gaji" aria-expanded="false"
                                                    aria-controls="gaji">
                                                    <i class="ti ti-circle"></i>
                                                    <span>Gaji</span>
                                                    <span class="absolute right-3 mt-[3px] text-sm">
                                                        <iconify-icon icon="fa6-solid:angle-right" data-accordion-icon>
                                                        </iconify-icon>
                                                    </span>
                                                </button>
                                            </a>
                                            <div class="accordion-menu border-l p-3 mt-2 @active('slipIndex', 'active-link') {{ request()->is('gaji', 'gaji/*') ? 'show' : 'hidden' }}"
                                                id="gaji" aria-labelledby="headingTwo">
                                                <ul class="sub-menu pl-2 ml-3 space-y-2 border-them border-l-2 text-sm">
                                                    @if (hasPermission('penghasilan - gaji - slip jurnal'))
                                                        <li>
                                                            <a href="{{ route('slipIndex') }}">
                                                                <button class="btn-link">
                                                                    <span>Slip jurnal</span>
                                                                </button>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (hasPermission('penghasilan - gaji - slip gaji'))
                                                        <li>
                                                            <a href="{{ route('slip.index') }}">
                                                                <button class="btn-link">
                                                                    <span>Slip Gaji</span>
                                                                </button>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endcan
                        @can('histori')
                            <li class="item-link relative">
                                <a href="#">
                                    <button class="btn-link "@active('history', 'active')" data-accordion-target="#histori"
                                        aria-expanded="false" aria-controls="histori">
                                        <i class="ti ti-history-toggle"></i>
                                        <span>Histori</span>
                                        <span class="absolute right-3 mt-[3px] text-sm">
                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                        </span>
                                    </button>
                                </a>
                                <div class="accordion-menu {{ request()->is('history') ? 'show' : 'hidden' }}" id="histori">
                                    <ul class="sub-menu">
                                        @can('histori - jabatan')
                                            <li class="item-link">
                                                <a href="{{ route('history_jabatan.index') }}">
                                                    <button class="btn-link @active('history_jabatan', 'active-link')">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Jabatan</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('histori - penjabat sementara')
                                            <li class="item-link">
                                                <a href="{{ route('pejabat-sementara.history') }}">
                                                    <button class="btn-link">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Penjabat Sementara</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('histori - surat peringatan')
                                            <li class="item-link">
                                                <a href="{{ route('surat-peringatan.history') }}">
                                                    <button class="btn-link">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Surat Peringatan</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan
                        {{-- @can('laporan') --}}
                            <li class="item-link relative">
                                <a href="#">
                                    <button
                                        class="btn-link {{ request()->is('laporan-pergerakan-karir/*', 'dpp', 'laporan_jamsostek', 'laporan-rekapitulasi/*','laporan-rekapitulasi') ? 'active-link' : '' }}"
                                        data-accordion-target="#laporan" aria-expanded="false" aria-controls="laporan">
                                        <i class="ti ti-message-report"></i>
                                        <span>Laporan</span>
                                        <span class="absolute right-3 mt-[3px] text-sm">
                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                        </span>
                                    </button>
                                </a>
                                <div class="accordion-menu {{ request()->is('laporan', 'index_dpp','laporan-rekapitulasi') ? 'show' : 'hidden' }}"
                                    id="laporan">
                                    <ul class="sub-menu">
                                        @can('laporan - laporan pergerakan karir')
                                            <li class="item-link relative" data-accordion="collapse"
                                                data-active-classes="active-link">
                                                <a href="#">
                                                    <button class="btn-link" data-accordion-target="#laporan-pergerakan-karir"
                                                        aria-expanded="false" aria-controls="laporan-pergerakan-karir">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Laporan Pergerakan Karir</span>
                                                        <span class="absolute right-3 mt-[3px] text-sm">
                                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                                        </span>
                                                    </button>
                                                </a>
                                                <div class="accordion-menu  {{ request()->is(
                                                    'laporan-pergerakan-karir/laporan-mutasi',
                                                    'laporan-pergerakan-karir/laporan-demosi',
                                                    'laporan-pergerakan-karir/laporan-promosi',
                                                    'laporan-pergerakan-karir/laporan-penonaktifan',
                                                )
                                                    ? 'show'
                                                    : 'hidden' }}"
                                                    id="laporan-pergerakan-karir">
                                                    <ul class="sub-menu pl-2 ml-3 space-y-2 border-them border-l-2 text-sm">
                                                        @can('laporan - laporan pergerakan karir - laporan mutasi')
                                                            <li>
                                                                <a href="{{ route('laporan-mutasi.index') }}">
                                                                    <button class="btn-link">
                                                                        <span>Laporan Mutasi</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('laporan - laporan pergerakan karir - laporan demosi')
                                                            <li>
                                                                <a href="{{ route('laporan-demosi.index') }}">
                                                                    <button class="btn-link">
                                                                        <span>Laporan Demosi</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('laporan - laporan pergerakan karir - laporan promosi')
                                                            <li>
                                                                <a href="{{ route('laporan-promosi.index') }}">
                                                                    <button class="btn-link">
                                                                        <span>Laporan Promosi</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('laporan - laporan pergerakan karir - laporan penonaktifan')
                                                            <li>
                                                                <a href="{{ route('laporan-penonaktifan.index') }}">
                                                                    <button class="btn-link">
                                                                        <span>Laporan Penonaktifan</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </li>
                                        @endcan
                                        @can('laporan - laporan jamsostek')
                                            <li class="item-link">
                                                <a href="{{ route('laporan_jamsostek.index') }}">
                                                    <button
                                                        class="btn-link {{ request()->is('laporan_jamsostek') ? 'show' : 'hidden' }}">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Laporan Jamsostek</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('laporan - laporan dpp')
                                            <li class="item-link">
                                                <a href="{{ route('index_dpp') }}">
                                                    <button class="btn-link {{ request()->is('index_dpp') }}">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Laporan Dpp</span>
                                                    </button>
                                                </a>
                                            </li>
                                        @endcan
                                        {{-- @can('laporan - laporan rekap tetap') --}}
                                            <li class="item-link">
                                                <a href="{{ route('laporan-rekapitulasi.index') }}">
                                                    <button class="btn-link {{ request()->is('laporan-rekapitulasi.index','laporan-rekapitulasi') ? 'active-link' : '' }}">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Laporan Rekap Tetap</span>
                                                    </button>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>
                                </div>
                            </li>
                        {{-- @endcan --}}
                        @can('log')
                            <li class="item-link relative">
                                <a href="#">
                                    <button class="btn-link" data-accordion-target="#log" aria-expanded="false"
                                        aria-controls="log">
                                        <i class="ti ti-list-check"></i>
                                        <span>Log</span>
                                        <span class="absolute right-3 mt-[3px] text-sm">
                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                        </span>
                                    </button>
                                </a>
                                @can('log - log aktivitas')
                                    <div class="accordion-menu hidden" id="log">
                                        <ul class="sub-menu">
                                            <li class="item-link">
                                                <a href="#">
                                                    <button class="btn-link">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Log Aktivitas</span>
                                                    </button>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                @endcan
                            </li>
                        @endcan
                    </ul>
                </div>
                @can('setting')
                    <div class="menus">
                        <div class="ml-5 category">
                            <p class="text-xs font-semibold tracking-widest text-gray-400">SETTINGS</p>
                        </div>
                        <ul class="sub-menu">
                            <li class="item-link">
                                <a href="#">
                                    <button class="btn-link relative @active('user,role,cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database,ptkp,profil-kantor-pusat,penambahan-bruto,pengurangan-bruto')" data-accordion-target="#settings"
                                        aria-expanded="false" aria-controls="settings">
                                        <i class="ti ti-settings"></i>
                                        <span>Settings</span>
                                        <span class="absolute right-3 mt-[3px] text-sm">
                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                        </span>
                                    </button>
                                </a>
                                <div class="accordion-menu {{ request()->is('user,role,cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,ptkp') ? 'show' : 'hidden' }}"
                                    id="settings">
                                    <ul class="sub-menu" data-accordion="collapse" data-active-classes="active-link">
                                        @can('setting - master')
                                            <li class="item-link relative">
                                                <a href="#">
                                                    <button class="btn-link " data-accordion-target="#master" aria-expanded="false"
                                                        aria-controls="master">
                                                        <i class="ti ti-circle"></i>
                                                        <span>Master</span>
                                                        <span class="absolute right-3 mt-[3px] text-sm">
                                                            <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                                        </span>
                                                    </button>
                                                </a>
                                                <div class="accordion-menu hidden" id="master">
                                                    <ul class="sub-menu pl-2 ml-4 space-y-2 border-them border-l-2 text-sm">
                                                        @can('setting - master - user')
                                                            <li>
                                                                <a href="{{ route('user.index') }}">
                                                                    <button class="btn-link  @active('user', 'active-link')">
                                                                        <span>User</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - role')
                                                            <li>
                                                                <a href="{{ route('role.index') }}">
                                                                    <button class="btn-link  @active('role', 'active-link')">
                                                                        <span>Roles</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - kantor cabang')
                                                            <li>
                                                                <a href="{{ route('cabang.index') }}">
                                                                    <button class="btn-link @active('cabang', 'active-link')">
                                                                        <span>Kantor Cabang</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - divisi')
                                                            <li>
                                                                <a href="{{ route('divisi.index') }}">
                                                                    <button class="btn-link @active('divisi', 'active-link')">
                                                                        <span>Divisi</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - sub divisi')
                                                            <li>
                                                                <a href="{{ route('sub_divisi.index') }}">
                                                                    <button class="btn-link @active('sub_divisi', 'active-link')">
                                                                        <span>Sub Divisi</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - bagian')
                                                            <li>
                                                                <a href="{{ route('bagian.index') }}">
                                                                    <button class="btn-link @active('bagian', 'active-link')">
                                                                        <span>Bagian</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - jabatan')
                                                            <li>
                                                                <a href="{{ route('jabatan.index') }}">
                                                                    <button class="btn-link @active('jabatan', 'active-link')">
                                                                        <span>Jabatan</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - pangkat & golongan')
                                                            <li>
                                                                <a href="{{ route('pangkat_golongan.index') }}">
                                                                    <button class="btn-link @active('pangkat_golongan', 'active-link')">
                                                                        <span>Pangkat & Golongan</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - tunjangan')
                                                            <li>
                                                                <a href="{{ route('tunjangan.index') }}">
                                                                    <button class="btn-link @active('tunjangan', 'active-link')">
                                                                        <span>Tunjangan</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - rentang umur')
                                                            <li>
                                                                <a href="{{ route('umur.index') }}">
                                                                    <button class="btn-link @active('umur', 'active-link')">
                                                                        <span>Rentang Umur</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('setting - master - penghasilan tanpa pajak')
                                                            <li>
                                                                <a href="{{ route('ptkp.index') }}">
                                                                    <button class="btn-link @active('ptkp', 'active-link')">
                                                                        <span>Penghasilan tanpa Pajak</span>
                                                                    </button>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @if (auth()->user()->hasRole('admin'))
                                                        <li>
                                                            <a href="{{ route('reset-sessions.index') }}">
                                                                <button class="btn-link @active('reset-sessions', 'active-link')">
                                                                    <span>Session</span>
                                                                </button>
                                                            </a>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                        @endcan
                                            @php
                                                $profilKantorPusat = \DB::table('mst_profil_kantor')
                                                    ->select('id', 'kd_cabang')
                                                    ->where('kd_cabang', '000')
                                                    ->first();
                                            @endphp
                                        @can('setting - kantor pusat')
                                                <li class="item-link relative">
                                                    <a href="#">
                                                        <button class="btn-link @active('profil-kantor-pusat,penambahan-bruto,pengurangan-bruto')" data-accordion-target="#kantor-pusat"
                                                            aria-expanded="false" aria-controls="kantor-pusat">
                                                            <i class="ti ti-circle"></i>
                                                            <span>Kantor Pusat</span>
                                                            <span class="absolute right-3 mt-[3px] text-sm">
                                                                <iconify-icon icon="fa6-solid:angle-right"></iconify-icon>
                                                            </span>
                                                        </button>
                                                    </a>
                                                    <div class="accordion-menu {{ request()->is('profil-kantor-pusat,penambahan-bruto,pengurangan-bruto') ? 'show' : 'hidden' }}"
                                                        id="kantor-pusat">
                                                        <ul class="sub-menu pl-2 ml-4 space-y-2 border-them border-l-2 text-sm">
                                                            @can('setting - kantor pusat - profil')
                                                                <li class="item-link">
                                                                    <a href="{{ route('profil-kantor-pusat.index') }}">
                                                                        <button class="btn-link @active('profil-kantor-pusat', 'active-link')">
                                                                            <span>Profile</span>
                                                                        </button>
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                            @can('setting - kantor pusat - penambahan bruto')
                                                                <li class="item-link">
                                                                    <a
                                                                        href="{{ route('penambahan-bruto.index') }}?profil_kantor={{ $profilKantorPusat ? $profilKantorPusat->id : '' }}">
                                                                        <button class="btn-link @active('penambahan-bruto', 'active-link')">
                                                                            <span>Penambahan Bruto</span>
                                                                        </button>
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                            @can('setting - kantor pusat - pengurangan bruto')
                                                                <li class="item-link">
                                                                    <a
                                                                        href="{{ route('pengurangan-bruto.index') }}?profil_kantor={{ $profilKantorPusat ? $profilKantorPusat->id : '' }}">
                                                                        <button class="btn-link @active('pengurangan-bruto', 'active-link')">
                                                                            <span>Pengurangan Bruto</span>
                                                                        </button>
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('setting - database')
                                                <li class="item-link">
                                                    <a href="#">
                                                        <button class="btn-link">
                                                            <i class="ti ti-circle"></i>
                                                            <span>Database</span>
                                                        </button>
                                                    </a>
                                                </li>
                                        @endcan
                                        </ul>
                                    </div>
                            </li>
                        </ul>
                    </div>
                @endcan
                </div>
            </div>
