<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '001',
            'nama_cabang' => 'Surabaya',
            'alamat_cabang' => 'JL. Ciliwung No.11',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '002',
            'nama_cabang' => 'Pamekasan',
            'alamat_cabang' => 'Jl. Jokotole No. 8 Kec. Pademawu, Pamekasan',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '003',
            'nama_cabang' => 'Bangkalan',
            'alamat_cabang' => 'Jl. Teuku Umar No. 33A, Bangkalan',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '004',
            'nama_cabang' => 'Banyuwangi',
            'alamat_cabang' => 'Jl. Letkol Istiqlah No. 09, Banyuwangi',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '005',
            'nama_cabang' => 'Lumajang',
            'alamat_cabang' => 'Jl. Veteran No. 18B, Lumajang',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '006',
            'nama_cabang' => 'Probolinggo',
            'alamat_cabang' => 'Jl. KH. Hasan Genggong No. 244 Kebonsari Wetan, Probolinggo',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '007',
            'nama_cabang' => 'Pasuruan',
            'alamat_cabang' => 'Jl. KH. Ahmad Dahlan No. 10, Pasuruan',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '008',
            'nama_cabang' => 'Malang',
            'alamat_cabang' => 'Jl. R. Tumenggung Suryo No. 35 Kav. 7, Malang',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '009',
            'nama_cabang' => 'Ngawi',
            'alamat_cabang' => 'Jl. S. Parman No. 8, Ngawi',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '010',
            'nama_cabang' => 'Blitar',
            'alamat_cabang' => 'Jl. Kalimantan No.59 Sananwetan, Blitar',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '011',
            'nama_cabang' => 'Kediri',
            'alamat_cabang' => 'Jl. kilisuci No. 81 C-D RT:28/RW:6 Kel. Singonegaran Kec.Pesantren Kota Kediri',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '012',
            'nama_cabang' => 'Mojokerto',
            'alamat_cabang' => 'Jl. Majapahit No. 381 Prajurit Kulon, Mojokerto',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '013',
            'nama_cabang' => 'Jombang',
            'alamat_cabang' => 'Jl. Dr. Sutomo No. 7 Kepanjen, Jombang',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '014',
            'nama_cabang' => 'Nganjuk',
            'alamat_cabang' => 'Jl. Merdeka Kav 2 No. 2B, Kec Nganjuk, Kab Nganjuk',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '015',
            'nama_cabang' => 'Madiun',
            'alamat_cabang' => 'Jl. Parikesit No. 6, Madiun',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '016',
            'nama_cabang' => 'Tulungagung',
            'alamat_cabang' => 'Jl. Ki Mangun Sarkoro Vila Satwika No. A 1, Tulungagung',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '017',
            'nama_cabang' => 'Trenggalek',
            'alamat_cabang' => 'Jl. Jaksa Agung Suprapto No. 17, Trenggalek',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '018',
            'nama_cabang' => 'Ponorogo',
            'alamat_cabang' => 'Jl. MH. Thamrin No. 51, Ponorogo',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '019',
            'nama_cabang' => 'Gresik',
            'alamat_cabang' => 'JL. Jaksa Agung Suprapto No. 08, Gresik',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '020',
            'nama_cabang' => 'Lamongan',
            'alamat_cabang' => 'Jl. Wahidin Sudiro Husodo No. 96 Banjar Mendalan, Lamongan',
            'id_kantor' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '021',
            'nama_cabang' => 'Magetan',
            'alamat_cabang' => 'Jl. Raya Gorang-Gareng Maospati, Magetan',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '022',
            'nama_cabang' => 'Pacitan',
            'alamat_cabang' => 'Jl. Tentara Pelajar No. 165, Pacitan',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '023',
            'nama_cabang' => 'Sidoarjo',
            'alamat_cabang' => 'Jl. Raya Gelam No. 49 Kec. Candi, Sidoarjo',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '024',
            'nama_cabang' => 'Jember',
            'alamat_cabang' => 'Jl. Darmawangsa Ruko Graha Wijaya Kav. 14 Kec. Sukorambi, Jember',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '025',
            'nama_cabang' => 'Tuban',
            'alamat_cabang' => 'Jl. Pramuka No. 10A Kel Ronggomulyo Kec./Kab. Tuban',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '026',
            'nama_cabang' => 'Batu',
            'alamat_cabang' => 'Jl. Ahmad Yani no 4 RT:3/RW:7 Kelurahan Ngaklik, Kota Batu',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '027',
            'nama_cabang' => 'Situbondo',
            'alamat_cabang' => 'Jl. Wijaya Kusuma 82A RT.04 RW.01 Des/Kel.Dawuhan Kec/Kab. Situbondo',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '028',
            'nama_cabang' => 'Bojonegoro',
            'alamat_cabang' => 'Jl. Teuku Umar 30, Bojonegoro',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '029',
            'nama_cabang' => 'Bondowoso',
            'alamat_cabang' => 'Jl. Kyai Haji Wahid Hasyim Nomor 168, Ruko Crown Plaza Kavling 3 Bondowoso Kabupaten Bondowoso',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '030',
            'nama_cabang' => 'Sumenep',
            'alamat_cabang' => 'Jl Trunojoyo, Desa Kolor, Kec Kota Sumenep, Kab Sumenep (Komplek Ruko Arya Wiraraja)',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '031',
            'nama_cabang' => 'Kepanjen',
            'alamat_cabang' => 'Jl. Kawi, Kec Kepanjen, Kab Malang (Ruko Kepanjen City)',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
        DB::table('mst_cabang')
        ->insert([
            'kd_cabang' => '032',
            'nama_cabang' => 'Sampang',
            'alamat_cabang' => 'Jl. Rajawali No. 48, Kel. Karangdalem, Kec. Sampang, Kab. Sampang',
            'id_kantor' => 2,
            'created_at' => now()
        ]); 
    }
}
