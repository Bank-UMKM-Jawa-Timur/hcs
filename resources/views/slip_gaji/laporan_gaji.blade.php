@extends('layouts.template')
@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title font-weiht-bold">Lampiran Gaji</h5>
        <p class="card-title"><a href="">Gaji</a> > Lampiran Gaji</p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5" >
        @if (auth()->user()->hasRole(['kepegawaian']))
            <a href="{{ route('gaji.create') }}" class="ml-3">
                <button class="is-btn is-primary">import potongan</button>
            </a>
        @endif
    </div>
</div>

    <div class="card-body">
        <div class="row m-0">
            <div class="col-lg-12">
                @php
                    $already_selected_value = date('y');
                    $earliest_year = 2022;
                @endphp
                <form action="{{ route('getLaporanGaji') }}" method="post" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="kategori" class="form-control" id="">
                                    <option value="">--- Pilih Kategori ---</option>
                                    <option value="1" @selected($request?->kategori == 1)>Laporan Gaji Kesuluruhan</option>
                                    <option value="2" @selected($request?->kategori == 2)>Gaji Masuk Tabungan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Kantor</label>
                                <select name="kantor" id="kantor" class="form-control">
                                    <option value="">--- Pilih Kantor ---</option>
                                    <option value="pusat" {{ $request?->kantor == "pusat" ? 'selected' : '' }}>Pusat</option>
                                    <option value="cabang" {{ $request?->kantor == "cabang" ? 'selected' : '' }}>Cabang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="cabang_col">
                            
                        </div>
                        <div class="col-md-4">
                            <label for="tahun">Tahun</label>
                            <div class="form-group">
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">--- Pilih Tahun ---</option>
                                    @foreach (range(date('Y'), $earliest_year) as $x)
                                        <option @selected($request?->tahun == $x) value="{{ $x }}">{{ $x }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="Bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="-">--- Pilih Bulan ---</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option @selected($request?->bulan == $i) value="{{ $i }}">{{ getMonth($i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="pb-4 pt-4">
                        <button class="is-btn is-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
            @php
                $i = 1;
                $total_gj = 0;
                $total_penyesuaian = 0;
                $totalTj = [];

                function rupiah($angka)
                {
                    $hasil_rupiah = number_format($angka, 0, ",", ".");
                    return $hasil_rupiah;
                }
            @endphp
            @if ($data != null)
                @if ($kategori == 1)
                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Gaji Pokok</th>
                                    <th>Tj. Keluarga</th>
                                    <th>Tj. Teller</th>
                                    <th>Tj. Listrik & Air</th>
                                    <th>Tj. Jabatan</th>
                                    <th>Tj. Perumahan</th>
                                    <th>Tj. Kemahalan</th>
                                    <th>Tj. Pelaksana</th>
                                    <th>Tj. Kesejahteraan</th>
                                    <th>Penyesuaian</th>
                                    <th>Total</th>
                                    <th>PPH 21</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>{{ rupiah($item['gj_pokok']) }}</td>
                                        @foreach ($item['tunjangan'] as $j => $itemTj)
                                            <td>{{ ($itemTj != null) ? rupiah($itemTj) : '-' }}</td>
                                        @endforeach
                                        <td>{{ ($item['gj_penyesuaian'] != null) ? rupiah($item['gj_penyesuaian']) : '-' }}</td>
                                        <td>{{ rupiah($item['total']) }}</td>
                                        <td>0</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Gaji</th>
                                    <th>No Rek</th>
                                    <th>JP BPJS TK 1%</th>
                                    <th>DPP 5%</th>
                                    <th>Kredit Koperasi</th>
                                    <th>Iuran Koperasi</th>
                                    <th>Iuran Pegawai</th>
                                    <th>Iuran</th>
                                    <th>Total Potongan</th>
                                    <th>Total yang Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>
                                            {{ $i++ }}
                                        </td>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>{{ rupiah($item['total']) }}</td>
                                        <td>{{ $item['norek'] }}</td>
                                        @foreach ($item['potongan'] as $j => $itemPotongan)
                                            <td>{{ ($itemPotongan != 0) ? rupiah($itemPotongan) : '-' }}</td>
                                        @endforeach
                                        <td>{{ (array_sum($item['potongan']) > 0) ? rupiah(array_sum($item['potongan'])) : '-' }}</td>
                                        <td>{{ rupiah($item['total'] - array_sum($item['potongan'])) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script>
        $("#table").DataTable({

        });

        $(document).ready(function() {
            function getCabang() {
                var value = $("#kantor").val();
                $("#cabang_col").empty();
                if (value === 'cabang') {
                    const kd_cabang = '<?php echo $request?->cabang; ?>';

                    $.ajax({
                        type: 'GET',
                        url: "{{ route('get_cabang') }}",
                        dataType: 'JSON',
                        success: (res) => {
                            $('#cabang_col').append(`
                                <div class="form-group">
                                    <label for="Cabang">Cabang</label>
                                    <select name="cabang" id="cabang" class="form-control">
                                        <option value="">--- Pilih Cabang ---</option>
                                    </select>
                                </div>
                            `);

                            $.each(res[0], function(i, item){
                                $("#cabang").append(`
                                    <option ${item.kd_cabang == kd_cabang ? 'selected' : ''} value="${item.kd_cabang}">${item.kd_cabang} - ${item.nama_cabang}</option>
                                `);
                            });
                        }
                    });
                }
            }

            $("#kantor").change(getCabang).trigger("change");
        });
    </script>
@endsection