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
            'manajemen karyawan - data karyawan - edit karyawan - edit potongan',
            'manajemen karyawan - data karyawan - detail karyawan',
            'manajemen karyawan - data karyawan - import karyawan',
            'manajemen karyawan - data karyawan - export karyawan',
            'manajemen karyawan - data karyawan - reset password - karyawan',
            // data masa pensiunan,
            'manajemen karyawan - data masa pensiunan',
            'manajemen karyawan - update data masa pensiunan',
            'manajemen karyawan - import data masa pensiunan',
            'manajemen karyawan - detail data masa pensiunan',
            'manajemen karyawan - pengkinian data',
            'manajemen karyawan - pengkinian data - create pengkinian data',
            'manajemen karyawan - pengkinian data - update pengkinian data',
            'manajemen karyawan - pengkinian data - import pengkinian data',
            'manajemen karyawan - pengkinian data - detail pengkinian data',
            'manajemen karyawan - pergerakan karir',
            'manajemen karyawan - pergerakan karir - data mutasi',
            'manajemen karyawan - pergerakan karir - data mutasi - create mutasi',
            'manajemen karyawan - pergerakan karir - data demosi',
            'manajemen karyawan - pergerakan karir - data demosi - create demosi',
            'manajemen karyawan - pergerakan karir - data promosi',
            'manajemen karyawan - pergerakan karir - data promosi - create promosi',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan',
            'manajemen karyawan - data penjabat sementara',
            'manajemen karyawan - tambah penjabat sementara',
            'manajemen karyawan - reward & punishment',
            'manajemen karyawan - reward & punishment - surat peringatan',
            'manajemen karyawan - reward & punishment - surat peringatan - create',
            'manajemen karyawan - reward & punishment - surat peringatan - detail',
            'penghasilan',
            'penghasilan - proses penghasilan',
            'penghasilan - pajak penghasilan',
            'penghasilan - tambah penghasilan',
            'penghasilan - tambah penghasilan - import penghasilan',
            'penghasilan - import',
            'penghasilan - import - penghasilan teratur',
            'penghasilan - import - penghasilan teratur - import',
            'penghasilan - import - penghasilan teratur - detail',
            'penghasilan - import - penghasilan tidak teratur',
            'penghasilan - import - penghasilan tidak teratur - create',
            'penghasilan - import - penghasilan tidak teratur - import',
            'penghasilan - import - penghasilan tidak teratur - detail',
            'penghasilan - import - bonus',
            'penghasilan - import - bonus - import',
            'penghasilan - import - bonus - detail',
            'penghasilan - import - potongan',
            'penghasilan - import - potongan - import',
            'penghasilan - import - potongan - detail',
            'penghasilan - payroll',
            'penghasilan - payroll - list payroll',
            'penghasilan - gaji',
            'penghasilan - gaji - lampiran gaji',
            'penghasilan - gaji - slip jurnal',
            'penghasilan - gaji - slip gaji',
            'penghasilan - gaji - slip gaji - rincian',
            'penghasilan - gaji - slip gaji - rincian - download pdf',
            'penghasilan - lock - penghasilan teratur',
            'penghasilan - lock - penghasilan tidak teratur',
            'penghasilan - lock - bonus',
            'penghasilan - unlock - penghasilan teratur',
            'penghasilan - unlock - penghasilan tidak teratur',
            'penghasilan - unlock - bonus',
            'penghasilan - edit - penghasilan teratur',
            'penghasilan - edit - penghasilan tidak teratur',
            'penghasilan - edit - bonus',
            // Histori
            'histori',
            'histori - jabatan',
            'histori - penjabat sementara',
            'histori - surat peringatan',
            // Laporan
            'laporan',
            'laporan - laporan pergerakan karir',
            'laporan - laporan pergerakan karir - laporan mutasi',
            'laporan - laporan pergerakan karir - laporan demosi',
            'laporan - laporan pergerakan karir - laporan promosi',
            'laporan - laporan pergerakan karir - laporan penonaktifan',
            'laporan - laporan jamsostek',
            'laporan - laporan dpp',
            // LOG
            'log',
            'log - log aktivitas',
            // SETTING
            'setting',
            'setting - master',
            'setting - master - user',
            'setting - master - user - create user',
            'setting - master - user - edit user',
            'setting - master - user - detail user',
            'setting - master - user - delete user',
            'setting - master - user - reset password user',
            'setting - master - role',
            'setting - master - role - create role',
            'setting - master - role - edit role',
            'setting - master - role - detail role',
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
            'setting - master - rentang umur',
            'setting - master - rentang umur - create rentang umur',
            'setting - master - rentang umur - edit rentang umur',
            'setting - master - penghasilan tanpa pajak',
            'setting - master - penghasilan tanpa pajak - create penghasilan tanpa pajak',
            'setting - master - penghasilan tanpa pajak - edit penghasilan tanpa pajak',
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
        $admin_permissions = [
            'dashboard',
            'manajemen karyawan',
            'manajemen karyawan - data karyawan',
            'manajemen karyawan - data karyawan - detail karyawan',
            'manajemen karyawan - data karyawan - export karyawan',
            'manajemen karyawan - data karyawan - reset password - karyawan',
            'manajemen karyawan - data masa pensiunan',
            'manajemen karyawan - pengkinian data',
            'manajemen karyawan - pergerakan karir',
            'manajemen karyawan - pergerakan karir - data mutasi',
            'manajemen karyawan - pergerakan karir - data demosi',
            'manajemen karyawan - pergerakan karir - data promosi',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan',
            'manajemen karyawan - data penjabat sementara',
            'manajemen karyawan - reward & punishment',
            'manajemen karyawan - reward & punishment - surat peringatan',
            'penghasilan',
            'penghasilan - pajak penghasilan',
            'penghasilan - import',
            'penghasilan - import - penghasilan teratur',
            'penghasilan - import - penghasilan teratur - detail',
            'penghasilan - import - penghasilan tidak teratur',
            'penghasilan - import - penghasilan tidak teratur - detail',
            'penghasilan - import - bonus',
            'penghasilan - import - bonus - detail',
            'penghasilan - import - potongan',
            'penghasilan - import - potongan - detail',
            'penghasilan - payroll',
            'penghasilan - payroll - list payroll',
            'penghasilan - gaji',
            'penghasilan - gaji - lampiran gaji',
            'penghasilan - gaji - slip jurnal',
            'penghasilan - gaji - slip gaji',
            'penghasilan - gaji - slip gaji - rincian',
            'penghasilan - gaji - slip gaji - rincian - download pdf',
            'penghasilan - unlock - penghasilan teratur',
            'penghasilan - unlock - penghasilan tidak teratur',
            'penghasilan - unlock - bonus',
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
            'log',
            'log - log aktivitas',
            'setting',
            'setting - master',
            'setting - master - user',
            'setting - master - user - create user',
            'setting - master - user - edit user',
            'setting - master - user - detail user',
            'setting - master - user - delete user',
            'setting - master - user - reset password user',
            'setting - master - role',
            'setting - master - role - create role',
            'setting - master - role - edit role',
            'setting - master - role - detail role',
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
            'setting - master - rentang umur',
            'setting - master - rentang umur - create rentang umur',
            'setting - master - rentang umur - edit rentang umur',
            'setting - master - penghasilan tanpa pajak',
            'setting - master - penghasilan tanpa pajak - create penghasilan tanpa pajak',
            'setting - master - penghasilan tanpa pajak - edit penghasilan tanpa pajak',
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
        $hrd_permissions = [
            'dashboard',
            'manajemen karyawan',
            'manajemen karyawan - data karyawan',
            'manajemen karyawan - data karyawan - create karyawan',
            'manajemen karyawan - data karyawan - edit karyawan',
            'manajemen karyawan - data karyawan - detail karyawan',
            'manajemen karyawan - data karyawan - import karyawan',
            'manajemen karyawan - data karyawan - export karyawan',
            'manajemen karyawan - data masa pensiunan',
            'manajemen karyawan - update data masa pensiunan',
            'manajemen karyawan - import data masa pensiunan',
            'manajemen karyawan - detail data masa pensiunan',
            'manajemen karyawan - pengkinian data',
            'manajemen karyawan - pengkinian data - create pengkinian data',
            'manajemen karyawan - pengkinian data - update pengkinian data',
            'manajemen karyawan - pengkinian data - import pengkinian data',
            'manajemen karyawan - pengkinian data - detail pengkinian data',
            'manajemen karyawan - pergerakan karir',
            'manajemen karyawan - pergerakan karir - data mutasi',
            'manajemen karyawan - pergerakan karir - data mutasi - create mutasi',
            'manajemen karyawan - pergerakan karir - data demosi',
            'manajemen karyawan - pergerakan karir - data demosi - create demosi',
            'manajemen karyawan - pergerakan karir - data promosi',
            'manajemen karyawan - pergerakan karir - data promosi - create promosi',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan',
            'manajemen karyawan - data penjabat sementara',
            'manajemen karyawan - tambah penjabat sementara',
            'manajemen karyawan - reward & punishment',
            'manajemen karyawan - reward & punishment - surat peringatan',
            'manajemen karyawan - reward & punishment - surat peringatan - create',
            'manajemen karyawan - reward & punishment - surat peringatan - detail',
        ];
        $kepegawaian_permissions = [
            'dashboard',
            'manajemen karyawan',
            'manajemen karyawan - data karyawan',
            'manajemen karyawan - data karyawan - edit karyawan - edit potongan',
            'manajemen karyawan - data karyawan - detail karyawan',
            'penghasilan',
            'penghasilan - proses penghasilan',
            'penghasilan - pajak penghasilan',
            'penghasilan - tambah penghasilan',
            'penghasilan - tambah penghasilan - import penghasilan',
            'penghasilan - import',
            'penghasilan - import - penghasilan teratur',
            'penghasilan - import - penghasilan teratur - import',
            'penghasilan - import - penghasilan teratur - detail',
            'penghasilan - import - penghasilan tidak teratur',
            'penghasilan - import - penghasilan tidak teratur - create',
            'penghasilan - import - penghasilan tidak teratur - import',
            'penghasilan - import - penghasilan tidak teratur - detail',
            'penghasilan - import - bonus',
            'penghasilan - import - bonus - import',
            'penghasilan - import - bonus - detail',
            'penghasilan - import - potongan',
            'penghasilan - import - potongan - import',
            'penghasilan - import - potongan - detail',
            'penghasilan - payroll',
            'penghasilan - payroll - list payroll',
            'penghasilan - gaji',
            'penghasilan - gaji - lampiran gaji',
            'penghasilan - gaji - slip jurnal',
            'penghasilan - gaji - slip gaji',
            'penghasilan - gaji - slip gaji - rincian',
            'penghasilan - gaji - slip gaji - rincian - download pdf',
            'penghasilan - lock - penghasilan teratur',
            'penghasilan - lock - penghasilan tidak teratur',
            'penghasilan - lock - bonus',
            'penghasilan - unlock - penghasilan teratur',
            'penghasilan - unlock - penghasilan tidak teratur',
            'penghasilan - unlock - bonus',
            'penghasilan - edit - penghasilan teratur',
            'penghasilan - edit - penghasilan tidak teratur',
            'penghasilan - edit - bonus',
        ];
        $cabang_permissions = [
            'dashboard',
            'manajemen karyawan',
            'manajemen karyawan - data karyawan',
            'manajemen karyawan - data karyawan - detail karyawan',
            'manajemen karyawan - data karyawan - import karyawan',
            'manajemen karyawan - data karyawan - export karyawan',
            'penghasilan',
            'penghasilan - proses penghasilan',
            'penghasilan - pajak penghasilan',
            'penghasilan - gaji',
            'penghasilan - gaji - lampiran gaji',
            'penghasilan - gaji - slip jurnal',
            'penghasilan - gaji - slip gaji',
            'penghasilan - gaji - slip gaji - rincian',
            'penghasilan - gaji - slip gaji - rincian - download pdf',
        ];
        $user_permissions = [
            'dashboard',
            'penghasilan',
            'penghasilan - gaji',
            'penghasilan - gaji - lampiran gaji',
            'penghasilan - gaji - slip jurnal',
            'penghasilan - gaji - slip gaji',
            'penghasilan - gaji - slip gaji - rincian',
            'penghasilan - gaji - slip gaji - rincian - download pdf',
        ];

        for ($i=0; $i < count($role_name); $i++) {
            $role_akses = Role::create([
                'name' => $role_name[$i],
            ]);
            if ($role_name[$i] == 'admin') {
                $role_akses->givePermissionTo($admin_permissions);
            }
            else if ($role_name[$i] == 'hrd') {
                $role_akses->givePermissionTo($hrd_permissions);
            }
            else if ($role_name[$i] == 'kepegawaian') {
                $role_akses->givePermissionTo($kepegawaian_permissions);
            }
            else if ($role_name[$i] == 'cabang') {
                $role_akses->givePermissionTo($cabang_permissions);
            }
            else if ($role_name[$i] == 'user') {
                $role_akses->givePermissionTo($user_permissions);
            }
            else {
                $role_akses->givePermissionTo(['dashboard','setting - master - role','setting - master - role - create role']);
            }
        }
    }
}
