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
                {{-- @php
                    $has_permission_dashboard = \App\Http\Controllers\Controller::hasPermission('dashboard');
                @endphp --}}
                @if (auth()->user()->can('dashboard'))
                    <li class="@active('home')">
                        <a class="nav-link" href="{{ route('home') }}" style="font-weight: bolder">
                            <div class="d-flex">
                                <iconify-icon icon="akar-icons:dashboard" class="icon"></iconify-icon>
                                <span> Dashboard</span>
                            </div>
                        </a>
                    </li>
                @endif
                {{-- Menu Manajemen Karyawan --}}
                @if (auth()->user()->can('manajemen karyawan'))
                    <li
                        class="{{ request()->is(
                            'karyawan',
                            'karyawan/*',
                            'reminder_pensiun',
                            'reminder_pensiun/*',
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
                        <a class="nav-link" href="#submenu1" data-toggle="collapse" data-target="#submenu1"
                            style="font-weight: bolder">
                            <i class="nc-icon nc-tile-56" style="font-weight: bolder"></i>
                            Manajemen Karyawan
                        </a>
                        <ul class="sub-menu list-unstyled flex-column collapse pl-2 {{ request()->is(
                            'karyawan',
                            'karyawan/*',
                            'reminder_pensiun',
                            'reminder_pensiun/*',
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
                                        <i class="nc-icon nc-badge"></i>
                                        <p>Karyawan </p>
                                        <p></p>
                                    </a>
                                </li>
                            @endif
                            @can('manajemen karyawan - data masa pensiunan')
                                <li style="" class="@active('reminder-pensiun.index,reminder-pensiun.show')">
                                    <a href="{{ route('reminder-pensiun.index') }}">
                                        <i class="nc-icon nc-badge"></i>
                                        <p>Data Masa Pensiun</p>
                                        <p></p>
                                    </a>
                                </li>
                            @endcan
                            @can('manajemen karyawan - pengkinian data')
                            <li style="" class="@active('pengkinian_data.index,pengkinian_data.create,pengkinian_data.edit,pengkinian_data.show,import')">
                                <a href="{{ route('pengkinian_data.index') }}">
                                    <i class="nc-icon nc-ruler-pencil"></i>
                                    <p>Pengkinian Data </p>
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
                                    <i class="nc-icon nc-chart-bar-32"></i>
                                    <p class="dropdown-toggle" id="navbarDropdownMenuLink">Pergerakan Karir </p>
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
                                        <i class="nc-icon nc-tie-bow"></i>
                                        <p>Penjabat Sementara</p>
                                        <p></p>
                                    </a>
                                </li>
                            @endcan
                            @can('manajemen karyawan - reward & punishment')
                                <li class="dropdown @active('surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit')" style="margin-top: -15px">
                                    <a data-toggle="dropdown" aria-expanded="false">
                                        <i class="nc-icon nc-bell-55"></i>
                                        <p class="dropdown-toggle" id="navbarDropdownMenuLink">Reward & Punishment
                                        </p>
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
                @endif
                {{-- Menu Penghasilan --}}
                @can('penghasilan')
                <li
                    class="@active('pajak_penghasilan') {{ request()->is('penghasilan-tidak-teratur', 'penghasilan-tidak-teratur/*', 'bonus', 'bonus/*', 'potongan', 'potongan/*','gaji_perbulan', 'gaji_perbulan/*', 'penghasilan/import-penghasilan-teratur', 'penghasilan/import-penghasilan-teratur/*','pengganti-biaya-kesehatan', 'pengganti-biaya-kesehatan/*', 'uang-duka', 'uang-duka/*') ? 'active' : '' }}">
                    <a class="nav-link" href="#submenu2" data-toggle="collapse" data-target="#submenu2"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-tag-content" style="font-weight: bolder"></i>
                        Penghasilan
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse pl-2 {{ request()->is('gaji_perbulan', 'gaji_perbulan/*', 'uang-duka', 'uang-duka/*') ? 'active' : '' }} @active('pajak_penghasilan', 'bonus/*', 'show')"
                        id="submenu2">
                        @can('penghasilan - proses penghasilan')
                        <li style="margin-top: -15px" class="@active('gaji_perbulan')">
                            <a href="{{ route('gaji_perbulan.index') }}">
                                <i class="nc-icon nc-money-coins"></i>
                                <p>Proses Penghasilan</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - pajak penghasilan')
                        <li style="margin-top: -15px" class="@active('pajak_penghasilan.index') @active('get-penghasilan')">
                            <a href="{{ route('pajak_penghasilan.index') }}">
                                <i class="nc-icon nc-scissors"></i>
                                <p>Pajak</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - import - penghasilan teratur')
                        <li style="margin-top: -15px"
                            class="@active('penghasilan.import-penghasilan-teratur.index')">
                            <a href="{{ route('penghasilan.import-penghasilan-teratur.index') }}">
                                <i class="nc-icon nc-credit-card"></i>
                                <p>Penghasilan Teratur</p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - import - penghasilan tidak teratur')
                        <li style="margin-top: -15px"
                            class="@active('penghasilan-tidak-teratur.index')">
                            <a href="{{ route('penghasilan-tidak-teratur.index') }}">
                                <i class="nc-icon nc-credit-card"></i>
                                <p>Penghasilan Tidak Teratur</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - import - bonus')
                        <li style="margin-top: -15px !important"
                            class="@active('bonus.index')">
                            <a href="{{ route('bonus.index') }}">
                                <i class="nc-icon nc-credit-card"></i>
                                <p>Bonus</p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - import - potongan')
                        <li style="margin-top: -15px"
                            class="@active('potongan.index')">
                            <a href="{{ route('potongan.index') }}">
                                <i class="nc-icon nc-credit-card"></i>
                                <p>Potongan</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('penghasilan - payroll - list payroll')
                        <li style="margin-top: -15px"
                            class="@active('payroll.index')">
                            <a href="{{ route('payroll.index') }}">
                                <i class="nc-icon nc-money-coins"></i>
                                <p>Payroll</p>
                            </a>
                        </li>
                        @endcan
                        {{--  @can('penghasilan - tambah penghasilan')
                        <li style="margin-top: -15px" class="@active('pajak_penghasilan.create')">
                            <a href="{{ route('pajak_penghasilan.create') }}">
                                <i class="nc-icon nc-ruler-pencil"></i>
                                <p>Tambah Penghasilan</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan  --}}
                        {{-- Menu Gaji --}}
                        @can('penghasilan - gaji')
                        <li class="@active('slipIndex') {{ request()->is('gaji', 'gaji/*') ? 'active' : '' }}">
                            <a class="nav-link-item" href="#submenu-gaji" data-toggle="collapse" data-target="#submenu-gaji"
                               >
                                <i class="nc-icon nc-credit-card "></i>
                                <span class="dropdown-toggle">Gaji</span>
                            </a>
                            <ul class="sub-menu {{ request()->is('gaji', 'gaji/*') ? 'show' : '' }} list-unstyled flex-column collapse pl-2 @active('slipIndex', 'show')"
                                id="submenu-gaji">
                                @can('penghasilan - gaji - lampiran gaji')
                                <li style="margin-top: -15px"
                                    class="{{ request()->is('gaji', 'gaji/*') ? 'active' : '' }}">
                                    <a href="{{ route('gaji.index') }}">
                                        <i class="nc-icon nc-money-coins"></i>
                                        <p>Lampiran Gaji</p>
                                        <p></p>
                                    </a>
                                </li>
                                @endcan
                                @can('penghasilan - gaji - slip gaji')
                                <li style="margin-top: -15px" class="@active('slipIndex')">
                                    <a href="{{ route('slipIndex') }}">
                                        <i class="nc-icon nc-money-coins"></i>
                                        <p>Slip Jurnal</p>
                                        <p></p>
                                    </a>
                                </li>
                                @endcan
                                @can('penghasilan - payroll - slip gaji')
                                <li style="margin-top: -15px" class="@active('payroll.slip')">
                                    <a href="{{ route('payroll.slip') }}">
                                        <i class="nc-icon nc-money-coins"></i>
                                        <p>Slip Gaji</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                {{-- Menu Histori --}}
                @can('histori')
                <li class="@active('history')">
                    <a class="nav-link" href="#submenu3" data-toggle="collapse" data-target="#submenu3"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-compass-05" style="font-weight: bolder"></i>
                        Histori
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('history', 'show')"
                        id="submenu3">
                        @can('histori - jabatan')
                        <li style="margin-top: -15px" class="@active('history_jabatan')">
                            <a href="{{ route('history_jabatan.index') }}">
                                <i class="nc-icon nc-briefcase-24"></i>
                                <p>Jabatan</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('histori - penjabat sementara')
                        <li style="margin-top: -15px" class="@active('pejabat-sementara.history')">
                            <a href="{{ route('pejabat-sementara.history') }}">
                                <i class="nc-icon nc-tie-bow"></i>
                                <p>Penjabat Sementara</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('histori - surat peringatan')
                        <li style="margin-top: -15px" class="@active('surat-peringatan.history')">
                            <a href="{{ route('surat-peringatan.history') }}?tahun={{ date('Y') }}">
                                <i class="nc-icon nc-email-85"></i>
                                <p>Surat Peringatan</p>
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
                    class="{{ request()->is('laporan-pergerakan-karir/*', 'dpp', 'laporan_jamsostek') ? 'active' : '' }}">
                    <a class="nav-link" href="#submenu4" data-toggle="collapse" data-target="#submenu4"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-paper" style="font-weight: bolder"></i>
                        Laporan
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('laporan,index_dpp', 'show')"
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
                                <i class="nc-icon nc-single-copy-04"></i>
                                <p class="dropdown-toggle" id="navbarDropdownMenuLink">Laporan Pergerakan
                                    Karir </p>
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
                                <i class="nc-icon nc-single-copy-04"></i>
                                <p>Laporan Jamsostek</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('laporan - laporan dpp')
                        <li style="margin-top: -15px" class="@active('index_dpp')">
                            <a href="{{ route('index_dpp') }}">
                                <i class="nc-icon nc-single-copy-04"></i>
                                <p>Laporan DPP</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                {{-- Menu Migrasi Data --}}
                @can('migrasi')
                <li class="@active('migrasi')">
                    <a class="nav-link" href="#submenu8" data-toggle="collapse" data-target="#submenu8"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-cloud-upload-94" style="font-weight: bolder"></i>
                        Migrasi
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse pl-2 @active('migrasi')"
                        id="submenu8">
                        @can('migrasi - jabatan')
                        <li style="margin-top: -15px" class="@active('migrasiJabatan')">
                            <a href="{{ route('migrasiJabatan') }}">
                                <i class="nc-icon nc-cloud-upload-94"></i>
                                <p>Jabatan</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('migrasi - penjabat sementara')
                        <li style="margin-top: -15px" class="@active('migrasiPJS')">
                            <a href="{{ route('migrasiPJS') }}">
                                <i class="nc-icon nc-cloud-upload-94"></i>
                                <p>Penjabat Sementara</p>
                                <p></p>
                            </a>
                        </li>
                        @endcan
                        @can('migrasi - surat peringatan')
                        <li style="margin-top: -15px" class="@active('migrasiSP')">
                            <a href="{{ route('migrasiSP') }}">
                                <i class="nc-icon nc-cloud-upload-94"></i>
                                <p>Surat Peringatan</p>
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
                    <a class="nav-link" href="#submenu5" data-toggle="collapse" data-target="#submenu6"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-tap-01" style="font-weight: bolder"></i>
                        Log
                    </a>
                    @can('log - log aktivitas')
                    <ul class="sub-menu list-unstyled flex-column collapse pl-2" id="submenu6"
                        aria-expanded="false">
                        <li style="margin-top: -15px">
                            <a href="#">
                                <i class="nc-icon nc-refresh-69"></i>
                                <p>Log Aktivitas</p>
                                <p></p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcan
                {{-- Menu Setting --}}
                @can('setting')
                <li class="@active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database')">
                    <a class="nav-link" href="#submenu6" data-toggle="collapse" data-target="#submenu7"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-settings" style="font-weight: bolder"></i>
                        Setting
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse pl-2
                    {{-- @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database', 'show') --}}
                    "
                        id="submenu7">
                        @can('setting - master')
                            <li class="dropdown @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur', 'show')" style="margin-top: -15px">
                                <a data-toggle="dropdown" aria-expanded="false">
                                    <i class="nc-icon nc-box"></i>
                                    <p class="dropdown-toggle" id="navbarDropdownMenuLink">Master </p>
                                    <p></p>
                                </a>
                                <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                    @can('setting - master - role')
                                    <a class="dropdown-item @active('role.index')"
                                        href="{{ route('role.index') }}">Roles</a>
                                    @endcan
                                    @can('setting - master - kantor cabang')
                                    <a class="dropdown-item @active('cabang.index')"
                                        href="{{ route('cabang.index') }}">Kantor Cabang</a>
                                    @endcan
                                    @can('setting - master - divisi')
                                    <a class="dropdown-item @active('divisi.index')"
                                        href="{{ route('divisi.index') }}">Divisi</a>
                                    @endcan
                                    @can('setting - master - sub divisi')
                                    <a class="dropdown-item @active('sub_divisi.index')"
                                        href="{{ route('sub_divisi.index') }}">Sub Divisi</a>
                                    @endcan
                                    @can('setting - master - bagian')
                                    <a class="dropdown-item @active('bagian.index')"
                                        href="{{ route('bagian.index') }}">Bagian</a>
                                    @endcan
                                    @can('setting - master - jabatan')
                                    <a class="dropdown-item @active('jabatan.index')"
                                        href="{{ route('jabatan.index') }}">Jabatan</a>
                                    @endcan
                                    @can('setting - master - pangkat & golongan')
                                    <a class="dropdown-item @active('pangkat_golongan.index')"
                                        href="{{ route('pangkat_golongan.index') }}">Pangkat & Golongan</a>
                                    @endcan
                                    @can('setting - master - tunjangan')
                                    <a class="dropdown-item @active('tunjangan.index')"
                                        href="{{ route('tunjangan.index') }}">Tunjangan</a>
                                    @endcan
                                    @can('setting - master - rentang umur')
                                    <a class="dropdown-item @active('umur.index')"
                                        href="{{ route('umur.index') }}">Rentang Umur</a>
                                    @endcan
                                    @can('setting - master - penghasilan tanpa pajak')
                                    <a class="dropdown-item @active('umur.index')"
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
                            {{-- @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur', 'show') --}}
                            " style="margin-top: -15px">
                            <a data-toggle="dropdown" aria-expanded="false">
                                <i class="nc-icon nc-bank"></i>
                                <p class="dropdown-toggle" id="navbarDropdownMenuLink">Kantor Pusat </p>
                                <p></p>
                            </a>
                            <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                @can('setting - kantor pusat - profil')
                                    <a class="dropdown-item @active('cabang.index')"
                                        href="{{ route('profil-kantor-pusat.index') }}">Profil</a>
                                @endcan
                                @can('setting - kantor pusat - penambahan bruto')
                                    <a class="dropdown-item @active('divisi.index')"
                                        href="{{ route('penambahan-bruto.index') }}?profil_kantor={{$profilKantorPusat ? $profilKantorPusat->id : ''}}">Penambahan Bruto</a>
                                @endcan
                                @can('setting - kantor pusat - pengurangan bruto')
                                    <a class="dropdown-item @active('sub_divisi.index')"
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
