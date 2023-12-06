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
                <li class="@active('home')">
                    <a class="nav-link" href="{{ route('home') }}" 
                        style="font-weight: bolder">
                        <div class="d-flex">
                            <iconify-icon icon="akar-icons:dashboard" class="icon"></iconify-icon>
                            <span> Dashboard</span>
                        </div>
                    </a>
                </li>
                {{-- Menu Manajemen Karyawan --}}
                {{-- <li
                    class="@active('karyawan,pengkinian_data,klasifikasi,mutasi,demosi,promosi,penonaktifan,import,pejabat-sementara.index,pejabat-sementara.create,pejabat-sementara.edit,surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit,reminder-pensiun.index,reminder-pensiun.show')">
                    --}}
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
                    <ul class="sub-menu list-unstyled ml-3 collapse {{ request()->is(
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
                        <li style="" class="@active('karyawan.index,karyawan.create,karyawan.edit,karyawan.show,import,klasifikasi')">
                            <a href="{{ route('karyawan.index') }}">
                                <i class="nc-icon nc-badge"></i>
                                <p>Karyawan </p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('reminder-pensiun.index,reminder-pensiun.show')">
                            <a href="{{ route('reminder-pensiun.index') }}">
                                <i class="nc-icon nc-badge"></i>
                                <p>Data Masa Pensiun</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('pengkinian_data.index,pengkinian_data.create,pengkinian_data.edit,pengkinian_data.show,import')">
                            <a href="{{ route('pengkinian_data.index') }}">
                                <i class="nc-icon nc-ruler-pencil"></i>
                                <p>Pengkinian Data </p>
                                <p></p>
                            </a>
                        </li>
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
                            style="">
                            <a data-toggle="dropdown" aria-expanded="false">
                                <i class="nc-icon nc-chart-bar-32"></i>
                                <p class="dropdown-toggle" id="navbarDropdownMenuLink">Pergerakan Karir </p>
                                <p></p>
                            </a>
                            <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                <a class="dropdown-item @active('mutasi.index')"
                                    href="{{ route('mutasi.index') }}">Mutasi</a>
                                <a class="dropdown-item @active('demosi.index')"
                                    href="{{ route('demosi.index') }}">Demosi</a>
                                <a class="dropdown-item @active('promosi.index')"
                                    href="{{ route('promosi.index') }}">Promosi</a>
                                <a class="dropdown-item @active('karyawan.penonaktifan')"
                                    href="{{ route('penonaktifan.index') }}">Penonaktifan</a>
                            </div>
                        </li>
                        <li style="" class="@active('pejabat-sementara.index,pejabat-sementara.create,pejabat-sementara.edit')">
                            <a href="{{ route('pejabat-sementara.index') }}">
                                <i class="nc-icon nc-tie-bow"></i>
                                <p>Penjabat Sementara</p>
                                <p></p>
                            </a>
                        </li>
                        <li class="dropdown @active('surat-peringatan.index,surat-peringatan.create,surat-peringatan.edit')" style="">
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
                    </ul>
                </li>
                {{-- Menu Penghasilan --}}
                <li
                    class="@active('pajak_penghasilan')">
                    <a class="nav-link" href="#submenu2" data-toggle="collapse" data-target="#submenu2"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-tag-content" style="font-weight: bolder"></i>
                        Penghasilan
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse ml-3 {{ request()->is('gaji_perbulan', 'gaji_perbulan/*') ? 'active' : '' }} @active('pajak_penghasilan', 'show')"
                        id="submenu2">
                        <li style="" class="@active('pajak_penghasilan')">
                            <a href="{{ route('pajak_penghasilan.index') }}">
                                <i class="nc-icon nc-scissors"></i>
                                <p>Pajak Penghasilan</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('pajak_penghasilan.create')">
                            <a href="{{ route('pajak_penghasilan.create') }}">
                                <i class="nc-icon nc-ruler-pencil"></i>
                                <p>Tambah Penghasilan</p>
                                <p></p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Menu Histori --}}
                <li class="@active('history')">
                    <a class="nav-link" href="#submenu3" data-toggle="collapse" data-target="#submenu3"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-compass-05" style="font-weight: bolder"></i>
                        Histori
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse ml-3 @active('history', 'show')"
                        id="submenu3">
                        <li style="" class="@active('history_jabatan')">
                            <a href="{{ route('history_jabatan.index') }}">
                                <i class="nc-icon nc-briefcase-24"></i>
                                <p>Jabatan</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('pejabat-sementara.history')">
                            <a href="{{ route('pejabat-sementara.history') }}">
                                <i class="nc-icon nc-tie-bow"></i>
                                <p>Penjabat Sementara</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('surat-peringatan.history')">
                            <a href="{{ route('surat-peringatan.history') }}?tahun={{ date('Y') }}">
                                <i class="nc-icon nc-email-85"></i>
                                <p>Surat Peringatan</p>
                                <p></p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Menu Laporan --}}
                <li
                    class="{{ request()->is('laporan-pergerakan-karir/*', 'dpp', 'laporan_jamsostek') ? 'active' : '' }}">
                    <a class="nav-link" href="#submenu4" data-toggle="collapse" data-target="#submenu4"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-paper" style="font-weight: bolder"></i>
                        Laporan
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse ml-3 @active('laporan,index_dpp', 'show')"
                        id="submenu4">
                        <li class="dropdown {{ request()->is(
                            'laporan-pergerakan-karir/laporan-mutasi',
                            'laporan-pergerakan-karir/laporan-demosi',
                            'laporan-pergerakan-karir/laporan-promosi',
                            'laporan-pergerakan-karir/laporan-penonaktifan',
                        )
                            ? 'active'
                            : '' }}"
                            style="">
                            <a data-toggle="dropdown" aria-expanded="false">
                                <i class="nc-icon nc-single-copy-04"></i>
                                <p class="dropdown-toggle" id="navbarDropdownMenuLink">Laporan Pergerakan
                                    Karir </p>
                                <p></p>
                            </a>
                            <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('laporan-mutasi.index') }}">Laporan
                                    Mutasi</a>
                                <a class="dropdown-item" href="{{ route('laporan-demosi.index') }}">Laporan
                                    Demosi</a>
                                <a class="dropdown-item" href="{{ route('laporan-promosi.index') }}">Laporan
                                    Promosi</a>
                                <a class="dropdown-item"
                                    href="{{ route('laporan-penonaktifan.index') }}">Laporan Penonaktifan</a>
                            </div>
                        </li>
                        <li style="" class="@active('laporan_jamsostek')">
                            <a href="{{ route('laporan_jamsostek.index') }}">
                                <i class="nc-icon nc-single-copy-04"></i>
                                <p>Laporan Jamsostek</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('index_dpp')">
                            <a href="{{ route('index_dpp') }}">
                                <i class="nc-icon nc-single-copy-04"></i>
                                <p>Laporan DPP</p>
                                <p></p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Menu Gaji --}}
                <li class="@active('gaji.*, slipIndex.*, gaji_perbulan.*')">
                    <a class="nav-link" href="#submenu5" data-toggle="collapse" data-target="#submenu5"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-credit-card" style="font-weight: bolder"></i>
                        Gaji
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse ml-3  @active('gaji.*, slipIndex.*, gaji_perbulan.*', 'show')"
                        id="submenu5">
                        <li style="" class="@active('gaji_perbulan.*')">
                            <a href="{{ route('gaji_perbulan.index') }}">
                                <i class="nc-icon nc-money-coins"></i>
                                <p>Proses Penghasilan</p>
                                <p></p>
                            </a>
                        </li>
                        <li style=""
                            class="{{ request()->is('gaji', 'gaji/*') ? 'active' : '' }}">
                            <a href="{{ route('gaji.index') }}">
                                <i class="nc-icon nc-money-coins"></i>
                                <p>Lampiran Gaji</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('slipIndex')">
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
                    <a class="nav-link" href="#submenu8" data-toggle="collapse" data-target="#submenu8"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-cloud-upload-94" style="font-weight: bolder"></i>
                        Migrasi
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse ml-3 @active('migrasi')"
                        id="submenu8">
                        <li style="" class="@active('migrasiJabatan')">
                            <a href="{{ route('migrasiJabatan') }}">
                                <i class="nc-icon nc-cloud-upload-94"></i>
                                <p>Jabatan</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('migrasiPJS')">
                            <a href="{{ route('migrasiPJS') }}">
                                <i class="nc-icon nc-cloud-upload-94"></i>
                                <p>Penjabat Sementara</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('migrasiSP')">
                            <a href="{{ route('migrasiSP') }}">
                                <i class="nc-icon nc-cloud-upload-94"></i>
                                <p>Surat Peringatan</p>
                                <p></p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Menu Log Aktivitas --}}
                <li>
                    <a class="nav-link" href="#submenu5" data-toggle="collapse" data-target="#submenu6"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-tap-01" style="font-weight: bolder"></i>
                        Log
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse ml-3" id="submenu6"
                        aria-expanded="false">
                        <li style="">
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
                    <a class="nav-link" href="#submenu6" data-toggle="collapse" data-target="#submenu7"
                        style="font-weight: bolder">
                        <i class="nc-icon nc-settings" style="font-weight: bolder"></i>
                        Setting
                    </a>
                    <ul class="sub-menu list-unstyled flex-column collapse ml-3
                    {{-- @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur,database', 'show') --}}
                    "
                        id="submenu7">
                        <li class="dropdown @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur', 'show')" style="">
                            <a data-toggle="dropdown" aria-expanded="false">
                                <i class="nc-icon nc-box"></i>
                                <p class="dropdown-toggle" id="navbarDropdownMenuLink">Master </p>
                                <p></p>
                            </a>
                            <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                <a class="dropdown-item @active('cabang.index')"
                                    href="{{ route('cabang.index') }}">Kantor Cabang</a>
                                <a class="dropdown-item @active('divisi.index')"
                                    href="{{ route('divisi.index') }}">Divisi</a>
                                <a class="dropdown-item @active('sub_divisi.index')"
                                    href="{{ route('sub_divisi.index') }}">Sub Divisi</a>
                                <a class="dropdown-item @active('bagian.index')"
                                    href="{{ route('bagian.index') }}">Bagian</a>
                                <a class="dropdown-item @active('jabatan.index')"
                                    href="{{ route('jabatan.index') }}">Jabatan</a>
                                <a class="dropdown-item @active('pangkat_golongan.index')"
                                    href="{{ route('pangkat_golongan.index') }}">Pangkat & Golongan</a>
                                <a class="dropdown-item @active('tunjangan.index')"
                                    href="{{ route('tunjangan.index') }}">Tunjangan</a>
                                <a class="dropdown-item @active('umur.index')"
                                    href="{{ route('umur.index') }}">Rentang Umur</a>
                                <a class="dropdown-item @active('umur.index')"
                                    href="{{ route('ptkp.index') }}">Penghasilan tanpa Pajak</a>
                            </div>
                        </li>
                        @php
                            $profilKantorPusat = \DB::table('mst_profil_kantor')->select('id','kd_cabang')->where('kd_cabang', '000')->first();
                        @endphp
                        <li class="dropdown
                        {{-- @active('cabang,divisi,sub_divisi,bagian,jabatan,pangkat_golongan,tunjangan,umur', 'show') --}}
                        " style="">
                            <a data-toggle="dropdown" aria-expanded="false">
                                <i class="nc-icon nc-bank"></i>
                                <p class="dropdown-toggle" id="navbarDropdownMenuLink">Kantor Pusat </p>
                                <p></p>
                            </a>
                            <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                                <a class="dropdown-item @active('cabang.index')"
                                    href="{{ route('profil-kantor-pusat.index') }}">Profil</a>
                                <a class="dropdown-item @active('divisi.index')"
                                    href="{{ route('penambahan-bruto.index') }}?profil_kantor={{$profilKantorPusat ? $profilKantorPusat->id : ''}}">Penambahan Bruto</a>
                                <a class="dropdown-item @active('sub_divisi.index')"
                                    href="{{ route('pengurangan-bruto.index') }}?profil_kantor={{$profilKantorPusat ? $profilKantorPusat->id : ''}}">Pengurangan Bruto</a>
                            </div>
                        </li>
                        <li style="">
                            <a href="#">
                                <i class="nc-icon nc-single-02"></i>
                                <p>User Akses</p>
                                <p></p>
                            </a>
                        </li>
                        <li style="" class="@active('database')">
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