<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Admin
        // 2. HRD
        // 3. Kepegawaian
        // 4. Cabang
        // 5. User

        $role_name = ['admin','hrd','kepegawaian','cabang','user'];
        $permission = [
            // dashboard
            'dashboard',
            // manajemen karyawan
            'manajemen karyawan',
            'manajemen karyawan - data karyawan',
            'manajemen karyawan - data karyawan - create karyawan',
            'manajemen karyawan - data karyawan - edit karyawan',
            'manajemen karyawan - data karyawan - detail karyawan',
            'manajemen karyawan - data karyawan - import karyawan',
            'manajemen karyawan - data karyawan - export karyawan',
            // data masa pensiunan,
            'manajemen karyawan - data masa pensiunan',
            'manajemen karyawan - update data masa pensiunan',
            'manajemen karyawan - import data masa pensiunan',
            'manajemen karyawan - detail data masa pensiunan',
            'manajemen karyawan - pergerakan karir',
            'manajemen karyawan - pergerakan karir - data mutasi',
            'manajemen karyawan - pergerakan karir - data mutasi - create mutasi',
            'manajemen karyawan - pergerakan karir - data demosi',
            'manajemen karyawan - pergerakan karir - data demosi - create demosi',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan',
            'manajemen karyawan - pergerakan karir - tambah penonaktifan karyawan',
            'manajemen karyawan - data penjabat sementara',
            'manajemen karyawan - tambah penjabat sementara',
            'manajemen karyawan - reward & punishment',
            'manajemen karyawan - reward & punishment - surat peringatan',
            'penghasilan',
            'penghasilan - proses penghasilan',
            'penghasilan - pajak penghasilan',
            'penghasilan - tambah penghasilan',
            'penghasilan - tambah penghasilan - import penghasilan',
            'histori',
            'histori - jabatan',
            'histori - penjabat sementara',
            'histori - surat peringatan',
            'laporan',
            'laporan - laporan pergerakan karir',
            'laporan - laporan pergerakan karir - laporan mutasi',
            'laporan - laporan pergerakan karir - laporan demosi',
            'laporan - laporan pergerakan karir - laporan promosi',
            'laporan - laporan pergerakan karir - laporan penonaktifan',
            'laporan - laporan jamsostek',
            'laporan - laporan dpp',
            'gaji',
            'gaji - lampiran gaji',
            'gaji - slip jurnal',
            'migrasi',
            'migrasi - jabatan',
            'migrasi - penjabat sementara',
            'migrasi - surat peringatan',
            'log',
            'log - log aktivitas',
            'setting',
            'setting - master',
            'setting - master - role',
            'setting - master - role - create role',
            'setting - master - role - edit role',
            'setting - master - role - delete role',
            'setting - master - kantor cabang',
            'setting - master - kantor cabang - create kantor cabang',
            'setting - master - kantor cabang - edit kantor cabang',
            'setting - master - divisi',
            'setting - master - divisi - create divisi',
            'setting - master - divisi - edit divisi',
            'setting - master - sub divisi',
            'setting - master - sub divisi - create sub divisi',
            'setting - master - sub divisi - edit sub divisi',
            'setting - master - bagian',
            'setting - master - bagian - create bagian',
            'setting - master - bagian - edit bagian',
            'setting - master - jabatan',
            'setting - master - jabatan - create jabatan',
            'setting - master - jabatan - edit jabatan',
            'setting - master - pangkat & golongan',
            'setting - master - pangkat & golongan - create pangkat & golongan',
            'setting - master - pangkat & golongan - edit pangkat & golongan',
            'setting - master - tunjangan',
            'setting - master - tunjangan - create tunjangan',
            'setting - master - tunjangan - edit tunjangan',
            'setting - kantor pusat',
            'setting - kantor pusat - profil',
            'setting - kantor pusat - penambahan bruto',
            'setting - kantor pusat - penambahan bruto - create penambahan bruto',
            'setting - kantor pusat - penambahan bruto - edit penambahan bruto',
            'setting - kantor pusat - penambahan bruto - delete penambahan bruto',
            'setting - kantor pusat - pengurangan bruto',
            'setting - kantor pusat - pengurangan bruto - create pengurangan bruto',
            'setting - kantor pusat - pengurangan bruto - edit pengurangan bruto',
            'setting - kantor pusat - pengurangan bruto - delete pengurangan bruto',
            'setting - database',
        ];
        for ($j=0; $j < count($permission); $j++) {
            Permission::create(['name' => $permission[$j]]);
        }
        for ($i=0; $i < count($role_name); $i++) {
            $role_akses = Role::create([
                'name' => $role_name[$i],
            ]);
            if ($role_name[$i] == 'admin') {
                $all_menu = \DB::table('permissions')->pluck('name');
                $role_akses->givePermissionTo($all_menu);
            }else{
                $role_akses->givePermissionTo(['dashboard']);
            }
        }
    }
}
