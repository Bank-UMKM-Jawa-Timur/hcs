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
            <ul class="nav flex-column sticky-top pl-2 mt-0">
                @if (Auth::guard("karyawan")->check())
                    <li style="margin-top: -15px" class="@active('home,per-cabang,per-divisi,list-karyawan-by-cabang,sub-divisi')">
                        <a class="nav-link-btn" href="{{ route('home') }}" style="font-weight: bolder">
                            <div class="d-flex justify-content-start">
                                <span class="icon">
                                    <iconify-icon icon="tdesign:dashboard-1" class="icon"></iconify-icon>
                                </span>
                                <span> Dashboard</span>
                            </div>
                        </a>
                    </li>
                @else
                    @can('dashboard')
                        <li style="margin-top: -15px" class="@active('home,per-cabang,per-divisi,list-karyawan-by-cabang,sub-divisi')">
                            <a class="nav-link-btn" href="{{ route('home') }}" style="font-weight: bolder">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="tdesign:dashboard-1" class="icon"></iconify-icon>
                                    </span>
                                    <span> Dashboard</span>
                                </div>
                            </a>
                        </li>
                    @endcan
                @endif
                {{-- Menu Manajemen Karyawan --}}
                @can('manajemen karyawan')
                    <li
                        class="{{ request()->is(
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
                            : '' }}">
                        <a class="nav-link-btn" href="#submenu1"  data-target="#submenu1"
                            style="font-weight: bolder">
                            <div class="d-flex justify-content-start">
                                <span class="icon">
                                    <iconify-icon icon="heroicons:user-group" class="icon"></iconify-icon>
                                </span>
                                <span> Manajemen Karyawan</span>
                            </div>
                        </a>
                        <ul class="inner list-unstyled flex-column  pl-2 {{ request()->is(
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
                            : '' }}"
                            id="submenu1">
                            @if (auth()->user()->can('manajemen karyawan - data karyawan'))
                                <li style="margin-top: -15px" class="@active('karyawan.index,karyawan.create,karyawan.edit,karyawan.show,import,klasifikasi')">
                                    <a href="{{ route('karyawan.index') }}">
                                        <div class="d-flex justify-content-start">
                                            <span class="icon">
                                                <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                            </span>
                                        <span>
                                            <p>Karyawan </p>
                                        </span>
                                        </div>
                                        <p></p>
                                    </a>
                                </li>
                            @endif
                            @can('manajemen karyawan - data masa pensiunan')
                                <li style="margin-top: -15px" class="@active('reminder-pensiun.index,reminder-pensiun.show')">
                                    <a href="{{ route('reminder-pensiun.index') }}">
                                        <div class="d-flex justify-content-start">
                                            <span class="icon">
                                                <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                            </span>
                                            <span>
                                                <p>Data Masa Pensiun</p>
                                            </span>
                                        </div>
                                        <p></p>
                                    </a>
                                </li>
                            @endcan
                            @can('manajemen karyawan - pengkinian data')
                            <li style="margin-top: -15px" class="@active('pengkinian_data.index,pengkinian_data.create,pengkinian_data.edit,pengkinian_data.show,import')">
                                <a href="{{ route('pengkinian_data.index') }}">
                                    <div class="d-flex justify-content-start">
                                        <span class="icon">
                                            <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                        </span>
                                        <span>
                                            <p>Pengkinian Data </p>
                                        </span>
                                    </div>
                                    <p></p>
                                </a>
                            </li>
                            @endcan
                            @can('manajemen karyawan - pergerakan karir')
                            <li class="dropdown {{ request()->is(
                                    'mutasi',
                                    'mutasi/*',
                                    'demosi',
                                    'demosi/*',
                                    'promosi',
                                    'promosi/*',
                                    'penonaktifan',
                                    'penonaktifan/*',
                                )
                                ? 'active'
                                : '' }}"
                                style="margin-top: -15px">
                                <a data-toggle="dropdown" aria-expanded="false">
                                    <div class="d-flex justify-content-start">
                                        <span class="icon">
                                            <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                        </span>
                                        <span>
                                            <p class="dropdown-toggle" id="navbarDropdownMenuLink">Pergerakan Karir </p>
                                        </span>
                                    </div>
                                    <p></p>
                                </a>
                                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                    @can('manajemen karyawan - pergerakan karir - data mutasi')
                                        <a class="dropdown-item @active('mutasi.index')"
                                            href="{{ route('mutasi.index') }}">Mutasi</a>
                                    @endcan
                                    @can('manajemen karyawan - pergerakan karir - data demosi')
                                        <a class="dropdown-item @active('demosi.index')"
                                        href="{{ route('demosi.index') }}">Demosi</a>
                                    @endcan
                                    @can('manajemen karyawan - pergerakan karir - data promosi')
                                        <a class="dropdown-item @active('promosi.index')"
                                            href="{{ route('promosi.index') }}">Promosi</a>

                                    @endcan
                                    @can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan')
                                        <a class="dropdown-item @active('karyawan.penonaktifan')"
                                            href="{{ route('penonaktifan.index') }}">Penonaktifan</a>
                                    @endcan
                                </div>
                            </li>
                            @endcan
                            @can('manajemen karyawan - data penjabat sementara')
                                <li style="margin-top: -15px" class="@active('pejabat-sementara.index,pejabat-sementara.create,pejabat-sementara.edit')">
                                    <a href="{{ route('pejabat-sementara.index') }}">
                                        <div class="d-flex justify-content-start">
                                            <span class="icon">
                                                <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                            </span>
                                            <span>
                                                <p>Penjabat Sementara</p>
                                            </span>
                                        </div>
                                        <p></p>
                                    </a>
                                </li>
                            @endcan
                            @can('manajemen karyawan - reward & punishment')
                                <li style="margin-top: -15px" class="dropdown @active('surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit')" style="margin-top: -15px">
                                    <a data-toggle="dropdown" aria-expanded="false">
                                    <div class="d-flex justify-content-start">
                                        <span class="icon">
                                            <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                        </span>
                                        <span>
                                            <p class="dropdown-toggle" id="navbarDropdownMenuLink">Reward & Punishment
                                            </p>
                                        </span>
                                    </div>
                                        <p></p>
                                    </a>
                                    <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                        <a class="dropdown-item @active('surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit')"
                                            href="{{ route('surat-peringatan.index') }}">Surat Peringatan</a>
                                    </div>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- Menu Penghasilan --}}
                @if (Auth::guard("karyawan")->check())
                <li
                class="@active('pajak_penghasilan','penghasilan/get-gaji') {{ request()->is(
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
                    'uang-duka', 'uang-duka/*',
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
                    ) ? 'active' : '' }}">
                    <a class="nav-link-btn" href="#submenu2"  data-target="#submenu2"
                        style="font-weight: bolder">
                        <div class="d-flex justify-content-start">
                            <span class="icon">
                                    <iconify-icon icon="game-icons:cash" class="icon"></iconify-icon>
                            </span>
                            <span>
                                Penghasilan
                            </span>
                        </div>
                    </a>
                    <ul class="inner list-unstyled flex-column  pl-2 {{ request()->is('gaji_perbulan', 'gaji_perbulan/*', 'uang-duka', 'uang-duka/*') ? 'active' : '' }} @active('pajak_penghasilan', 'penghasilan/get-gaji', 'bonus/*', 'show')"
                        id="submenu2">
                        @if(hasPermission('penghasilan - gaji'))
                            <li class=" dropdown @active('slipIndex') {{ request()->is('gaji', 'gaji/*') ? 'active' : '' }}">
                                <a href="#submenu-gaji" data-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span class="dropdown-toggle">Gaji</span>
                                </div>
                                </a>
                                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                    {{-- @if(hasPermission('penghasilan - gaji - lampiran gaji'))
                                        <a class="dropdown-item" href="{{ route('gaji.index') }}">Lampiran Gaji</a>
                                    @endif --}}
                                    @if(hasPermission('penghasilan - gaji - slip jurnal'))
                                        <a class="dropdown-item" href="{{ route('slipIndex') }}">Slip Jurnal</a>
                                    @endif
                                    @if(hasPermission('penghasilan - gaji - slip gaji'))
                                        <a class="dropdown-item" href="{{ route('slip.index') }}">Slip Gaji</a>
                                    @endif
                                </div>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                @can('penghasilan')
                <li
                    class="@active('pajak_penghasilan','penghasilan/get-gaji') {{ request()->is(
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
                        ) ? 'active' : '' }}">
                    <a class="nav-link-btn" href="#submenu2"  data-target="#submenu2"
                        style="font-weight: bolder">
                        <div class="d-flex justify-content-start">
                            <span class="icon">
                                    <iconify-icon icon="game-icons:cash" class="icon"></iconify-icon>
                            </span>
                            <span>
                                Penghasilan
                            </span>
                        </div>
                    </a>
                    <ul class="inner list-unstyled flex-column  pl-2 {{ request()->is('gaji_perbulan', 'gaji_perbulan/*', 'uang-duka', 'uang-duka/*') ? 'active' : '' }} @active('pajak_penghasilan','penghasilan/get-gaji', 'bonus/*', 'show')"
                        id="submenu2">
                        @can('penghasilan')
                        <li style="margin-top: -15px" class="@active('gaji_perbulan')">
                            <a href="{{ route('gaji_perbulan.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Proses Penghasilan</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - pajak penghasilan')
                        <li style="margin-top: -15px" class="@active('pajak_penghasilan.index') @active('get-penghasilan')">
                            <a href="{{ route('pajak_penghasilan.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Pajak</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - import - penghasilan teratur')
                        <li style="margin-top: -15px"
                            class="@active('penghasilan.import-penghasilan-teratur.index')">
                            <a href="{{ route('penghasilan.import-penghasilan-teratur.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Penghasilan Teratur</p>
                                    </span>
                                </div>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - import - penghasilan tidak teratur')
                        <li style="margin-top: -15px"
                            class="@active('penghasilan-tidak-teratur.index')">
                            <a href="{{ route('penghasilan-tidak-teratur.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Penghasilan Tidak Teratur</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - import - bonus')
                        <li style="margin-top: -15px !important"
                            class="@active('bonus.index')">
                            <a href="{{ route('bonus.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Bonus</p>
                                    </span>
                                </div>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - payroll - list payroll')
                        <li style="margin-top: -15px"
                            class="@active('payroll.index')">
                            <a href="{{ route('payroll.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Payroll</p>
                                    </span>
                                </div>
                            </a>
                        </li>
                        @endcan
                        {{-- Menu Gaji --}}
                        @can('penghasilan - gaji')
                            <li class=" dropdown @active('slipIndex') {{ request()->is('gaji', 'gaji/*') ? 'active' : '' }}">
                                <a href="#submenu-gaji" data-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span class="dropdown-toggle">Gaji</span>
                                </div>
                                </a>
                                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                    {{-- @can('penghasilan - gaji - lampiran gaji')
                                        <a class="dropdown-item" href="{{ route('gaji.index') }}">Lampiran Gaji</a>
                                    @endcan --}}
                                    @can('penghasilan - gaji - slip jurnal')
                                        <a class="dropdown-item" href="{{ route('slipIndex') }}">Slip Jurnal</a>
                                    @endcan
                                    @can('penghasilan - gaji - slip gaji')
                                        <a class="dropdown-item" href="{{ route('slip.index') }}">Slip Gaji</a>
                                    @endcan
                                </div>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                {{-- Menu Histori --}}
                @can('histori')
                <li class="@active('history')">
                    <a class="nav-link-btn" href="#submenu3"  data-target="#submenu3"
                        style="font-weight: bolder">
                        <div class="d-flex justify-content-start">
                            <span class="icon">
                                <iconify-icon icon="uis:history-alt" class="icon"></iconify-icon>
                            </span>
                            <span>
                                Histori
                            </span>
                        </div>
                    </a>
                    <ul class="inner list-unstyled flex-column  pl-2 @active('history', 'show')"
                        id="submenu3">
                        @can('histori - jabatan')
                        <li style="margin-top: -15px" class="@active('history_jabatan')">
                            <a href="{{ route('history_jabatan.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Jabatan</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('histori - penjabat sementara')
                        <li style="margin-top: -15px" class="@active('pejabat-sementara.history')">
                            <a href="{{ route('pejabat-sementara.history') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Penjabat Sementara</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('histori - surat peringatan')
                        <li style="margin-top: -15px" class="@active('surat-peringatan.history')">
                            {{-- <a href="{{ route('surat-peringatan.history') }}?tahun={{ date('Y') }}"> --}}
                            <a href="{{ route('surat-peringatan.history') }}? }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Surat Peringatan</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                {{-- Menu Laporan --}}
                @can('laporan')
                <li
                    class="{{ request()->is('laporan-pergerakan-karir/*', 'dpp', 'laporan_jamsostek', 'laporan-rekapitulasi/*') ? 'active' : '' }}">
                    <a class="nav-link-btn" href="#submenu4"  data-target="#submenu4"
                        style="font-weight: bolder">
                        <div class="d-flex justify-content-start">
                            <span class="icon">
                                <iconify-icon icon="icon-park-outline:table-report"></iconify-icon>
                            </span>
                            <span>
                                Laporan
                            </span>
                        </div>
                    </a>
                    <ul class="inner list-unstyled flex-column  pl-2 @active('laporan,index_dpp', 'show')"
                        id="submenu4">
                        @can('laporan - laporan pergerakan karir')
                        <li class="dropdown {{ request()->is(
                                'laporan-pergerakan-karir/laporan-mutasi',
                                'laporan-pergerakan-karir/laporan-demosi',
                                'laporan-pergerakan-karir/laporan-promosi',
                                'laporan-pergerakan-karir/laporan-penonaktifan',
                            )
                            ? 'active'
                            : '' }}"
                            style="margin-top: -15px">
                            <a data-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p class="dropdown-toggle" id="navbarDropdownMenuLink">Laporan Pergerakan
                                            Karir </p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                            <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                @can('laporan - laporan pergerakan karir - laporan mutasi')
                                <a class="dropdown-item" href="{{ route('laporan-mutasi.index') }}">Laporan
                                    Mutasi</a>
                                @endcan
                                @can('laporan - laporan pergerakan karir - laporan demosi')
                                <a class="dropdown-item" href="{{ route('laporan-demosi.index') }}">Laporan
                                    Demosi</a>
                                @endcan
                                @can('laporan - laporan pergerakan karir - laporan promosi')
                                <a class="dropdown-item" href="{{ route('laporan-promosi.index') }}">Laporan
                                    Promosi</a>
                                @endcan
                                @can('laporan - laporan pergerakan karir - laporan penonaktifan')
                                <a class="dropdown-item"
                                    href="{{ route('laporan-penonaktifan.index') }}">Laporan Penonaktifan</a>
                                @endcan
                            </div>
                        </li>
                        @endcan
                        @can('laporan - laporan jamsostek')
                        <li style="margin-top: -15px" class="@active('laporan_jamsostek')">
                            <a href="{{ route('laporan_jamsostek.index') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Laporan Jamsostek</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('laporan - laporan dpp')
                        <li style="margin-top: -15px" class="@active('index_dpp')">
                            <a href="{{ route('index_dpp') }}">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Laporan DPP</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('laporan - laporan rekap tetap')
                            <li style="margin-top: -15px" class="@active('laporan-rekapitulasi.index')">
                                <a href="{{ route('laporan-rekapitulasi.index') }}">
                                    <div class="d-flex justify-content-start">
                                        <span class="icon">
                                            <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                        </span>
                                        <span>
                                            <p>Laporan Rekap Tetap</p>
                                        </span>
                                    </div>
                                    <p></p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                {{-- Menu Log Aktivitas --}}
                @can('log')
                <li>
                    <a class="nav-link-btn" href="#submenu5"  data-target="#submenu6"
                        style="font-weight: bolder">
                        <div class="d-flex justify-content-start">
                            <span class="icon">
                                <iconify-icon icon="pajamas:log" class="icon"></iconify-icon>
                            </span>
                            <span>
                                <p class="mt-2">Log </p>
                            </span>
                        </div>
                    </a>
                    @can('log - log aktivitas')
                    <ul class="inner list-unstyled flex-column  pl-2" id="submenu6"
                        aria-expanded="false">
                        <li style="margin-top: -15px">
                            <a href="#">
                                <div class="d-flex justify-content-start">
                                    <span class="icon">
                                        <iconify-icon icon="ph:circle-duotone" class="icon"></iconify-icon>
                                    </span>
                                    <span>
                                        <p>Log Aktivitas</p>
                                    </span>
                                </div>
                                <p></p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcan
                {{-- Menu Setting --}}
                @can('setting')
                <li class="@active('user,role,cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database,ptkp,profil-kantor-pusat,penambahan-bruto,pengurangan-bruto')">
                    <a class="nav-link-btn" href="#submenu6"  data-target="#submenu7"
                        style="font-weight: bolder">
                        <div class="d-flex justify-content-start">
                            <span class="icon">
                                <iconify-icon icon="ant-design:setting-outlined" class="icon"></iconify-icon>
                            </span>
                            <span>
                                Setting
                            </span>
                        </div>
                    </a>
                    <ul class="inner list-unstyled flex-column  pl-2
                    {{-- @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database', 'show') --}}
                    "
                        id="submenu7">
                        @can('setting - master')
                            <li class="dropdown @active('user,role,cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,ptkp', 'show')" style="margin-top: -15px">
                                <a data-toggle="dropdown" aria-expanded="false">
                                    <i class="nc-icon nc-box"></i>
                                    <p class="dropdown-toggle" id="navbarDropdownMenuLink">Master </p>
                                    <p></p>
                                </a>
                                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                    @can('setting - master - user')
                                    @endcan
                                    <a class="dropdown-item @active('user')"
                                        href="{{ route('user.index') }}">User</a>
                                    @can('setting - master - role')
                                    <a class="dropdown-item @active('role')"
                                        href="{{ route('role.index') }}">Roles</a>
                                    @endcan
                                    @can('setting - master - kantor cabang')
                                    <a class="dropdown-item @active('cabang')"
                                        href="{{ route('cabang.index') }}">Kantor Cabang</a>
                                    @endcan
                                    @can('setting - master - divisi')
                                    <a class="dropdown-item @active('divisi')"
                                        href="{{ route('divisi.index') }}">Divisi</a>
                                    @endcan
                                    @can('setting - master - sub divisi')
                                    <a class="dropdown-item @active('sub_divisi')"
                                        href="{{ route('sub_divisi.index') }}">Sub Divisi</a>
                                    @endcan
                                    @can('setting - master - bagian')
                                    <a class="dropdown-item @active('bagian')"
                                        href="{{ route('bagian.index') }}">Bagian</a>
                                    @endcan
                                    @can('setting - master - jabatan')
                                    <a class="dropdown-item @active('jabatan')"
                                        href="{{ route('jabatan.index') }}">Jabatan</a>
                                    @endcan
                                    @can('setting - master - pangkat & golongan')
                                    <a class="dropdown-item @active('pangkat_golongan')"
                                        href="{{ route('pangkat_golongan.index') }}">Pangkat & Golongan</a>
                                    @endcan
                                    @can('setting - master - tunjangan')
                                    <a class="dropdown-item @active('tunjangan')"
                                        href="{{ route('tunjangan.index') }}">Tunjangan</a>
                                    @endcan
                                    @can('setting - master - rentang umur')
                                    <a class="dropdown-item @active('umur')"
                                        href="{{ route('umur.index') }}">Rentang Umur</a>
                                    @endcan
                                    @can('setting - master - penghasilan tanpa pajak')
                                    <a class="dropdown-item @active('ptkp')"
                                        href="{{ route('ptkp.index') }}">Penghasilan tanpa Pajak</a>
                                    @endcan
                                </div>
                            </li>
                        @endcan
                        @php
                            $profilKantorPusat = \DB::table('mst_profil_kantor')->select('id','kd_cabang')->where('kd_cabang', '000')->first();
                        @endphp
                        @can('setting - kantor pusat')
                        <li class="dropdown
                            @active('profil-kantor-pusat,penambahan-bruto,pengurangan-bruto', 'show')
                            " style="margin-top: -15px">
                            <a data-toggle="dropdown" aria-expanded="false">
                                <i class="nc-icon nc-bank"></i>
                                <p class="dropdown-toggle" id="navbarDropdownMenuLink">Kantor Pusat </p>
                                <p></p>
                            </a>
                            <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                @can('setting - kantor pusat - profil')
                                    <a class="dropdown-item @active('profil-kantor-pusat')"
                                        href="{{ route('profil-kantor-pusat.index') }}">Profil</a>
                                @endcan
                                @can('setting - kantor pusat - penambahan bruto')
                                    <a class="dropdown-item @active('penambahan-bruto')"
                                        href="{{ route('penambahan-bruto.index') }}?profil_kantor={{$profilKantorPusat ? $profilKantorPusat->id : ''}}">Penambahan Bruto</a>
                                @endcan
                                @can('setting - kantor pusat - pengurangan bruto')
                                    <a class="dropdown-item @active('pengurangan-bruto')"
                                        href="{{ route('pengurangan-bruto.index') }}?profil_kantor={{$profilKantorPusat ? $profilKantorPusat->id : ''}}">Pengurangan Bruto</a>
                                @endcan
                            </div>
                        </li>
                        @endcan
                        @can('setting - database')
                        <li style="margin-top: -15px" class="@active('database')">
                            <a href="{{ route('database.index') }}">
                                <i class="nc-icon nc-vector"></i>
                                <p>Database</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
