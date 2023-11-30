<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Template payroll!</title>
</head>

<body>
    <style>
        .text-utama{
            color: #DA251D;
        }

        .bg-utama{
            background-color: #DA251D;
        }
    </style>
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="header d-flex justify-content-between gap-5">
                    <img src="{{ asset('style/assets/img/logo.png') }}" width="150px" class="img-fluid">
                    <div class="content">
                        <h4 class="fw-bold p-3 ms-5"> PT HCS</h4>
                        <p class="text-start">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto facere ipsam quod laudantium voluptates commodi error aperiam libero, esse suscipit dicta deleniti consequatur eos, nostrum odit, nemo quas neque? Magnam.</p>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="fw-bold text-center mt-5 mb-5">SLIP GAJI KARYAWAN</h4>
        <div class="content-header bg-utama p-2 rounded">
            <h6 class="fw-bold text-center text-white">Data Karyawan</h6>
        </div>
        <div class="row">
            <div class="col-lg-4 mt-3">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Nama</td>
                        <td>:</td>
                        <td>Alex Subagyo</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tempat Lahir</td>
                        <td>:</td>
                        <td>Prancis</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-4 mt-3">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">NIP</td>
                        <td>:</td>
                        <td>Alex Subagyo</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Lahir</td>
                        <td>:</td>
                        <td>29 Februari 2000</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-4 mt-3">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Jabatan</td>
                        <td>:</td>
                        <td>Alex Subagyo</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jenis Kelamin</td>
                        <td>:</td>
                        <td>Laki laki</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="content-header bg-utama p-2 rounded">
            <h6 class="fw-bold text-center text-white">Data Slip Gaji</h6>
        </div>
        <div class="row">
            <div class="col-lg-12 mt-3">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>Gaji Pokok</td>
                            <td>:</td>
                            <td>5.000.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Keluarga</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Telepon</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Jabatan</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Teller</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Perumahan</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Kemahalan</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Pelaksana</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Multilevel</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan TI</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Transport</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Pulsa</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Tunjangan vitamin</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                        <tr>
                            <td>Uang Makan</td>
                            <td>:</td>
                            <td>10.000</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><hr></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-end">Total</td>
                            <td>:</td>
                            <td class="fw-bold">5.130.000</td>
                        </tr>
                        <tr class="text-white bg-utama">
                            <td colspan="3" class="text-center fw-bold">
                                Lima Juta Tiga Ratur Ribu Rupiah
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-lg-4">
                <table class="table table-borderless">
                    <tbody class="text-center">
                        <tr>
                            <td>Mengetahui</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p>Manajer Keuangan</p>
                                <p>Julio Critiano</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>








    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
