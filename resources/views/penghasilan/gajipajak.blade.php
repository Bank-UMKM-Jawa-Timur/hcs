@extends('layouts.template')
@include('vendor.select2')
@section('content')
<style>
    .dataTables_wrapper .dataTables_filter{
        float: right;
    }
    .dataTables_wrapper .dataTables_length{
        float: left;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        width: 90%;
    }

    .table-responsive {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .table-responsive::-webkit-scrollbar {
        overflow-y: hidden;
        overflow-x: scroll;
    }
</style>

<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Pajak Penghasilan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="">Pajak Penghasilan </a></p>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="row m-0">
        <div class="col">
            <a class="mb-3" href="{{ route('penghasilan.create') }}">
                <button class="btn btn-primary">tambah penghasilan</button>
            </a>
        </div>
    </div>
  <form action="{{ route('get-penghasilan') }}" method="post">
      @csrf
      <div class="row m-0">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Karyawan:</label>
                <select name="nip" id="nip" class="form-control"></select>
            </div>
        </div>
          @php
            $already_selected_value = date('y');
            $earliest_year = 2022;
          @endphp
          <div class="col-md-4">
              <label for="tahun">Tahun</label>
              <div class="form-group">
                  <select name="tahun" class="form-control">
                      <option value="">--- Pilih Tahun ---</option>
                      @foreach (range(date('Y'), $earliest_year) as $x)
                          <option value="{{ $x }}" @selected($request->tahun == $x)>{{ $x }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
        </div>
        <div class="col-md-4">
          <a href="penghasilan/gajipajak">
            <button class="btn btn-info" type="submit">Tampilkan</button>
          </a>
        </div>
  </form>
    <div class="card ml-3 mr-3 mb-3 mt-4 shadow">
        <div class="col-md-12">
            @php
                $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

                function rupiah($angka){
                    $hasil_rupiah = number_format($angka, 0, ",", ".");
                    return $hasil_rupiah;
                }
            @endphp
            <div class="table-responsive p-0">
                <table class="table" id="table_export" style="width: 100%">
                    <tr>
                        <td class="p-0">
                            <h5 class="card-title mt-3">PENGHASILAN TERATUR</h5>
                            <table class="table text-center cell-border stripe" style="width: 100%">
                                <thead>
                                    @php
                                        $total_k = null;
                                        $total_non = null;
                                    @endphp
                                    <tr>
                                        <th rowspan="2" style="background-color: #CCD6A6; min-width: 100px; text-align: center;">Bulan</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; min-width: 100px; text-align: center;">Gaji Pokok</th>
                                        <th colspan="8" style="background-color: #CCD6A6; text-align: center;">Tunjangan</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">JAMSOSTEK</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; text-align: center;">Penambah <br>Bruto Jamsostek</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; text-align: center;">T. Uang Makan</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; text-align: center;">T. Uang Pulsa</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; text-align: center;">T. Uang Vitamin</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; text-align: center;">T. Uang Transport</th>
                                    </tr>
                                    <tr style="background-color: #DAE2B6">
                                        <th>Keluarga</th>
                                        <th>Jabatan</th>
                                        <th>penyesuaian</th>
                                        <th>Perumahan</th>
                                        <th style="min-width: 100px">Listrik & Air</th>
                                        <th>Pelaksana</th>
                                        <th>Kemahalan</th>
                                        <th>Kesejahteraan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 12; $i++)
                                        <tr>
                                            <td>{{ $bulan[$i] }}</td>
                                            <td>{{ ($gj[$i]['gj_pokok'] != 0) ? rupiah($gj[$i]['gj_pokok']) : '-' }}</td>
                                            <td style="background-color: #FED049">{{ ($gj[$i]['tj_keluarga'] != 0) ? rupiah($gj[$i]['tj_keluarga']) : '-' }}</td>
                                            <td style="background-color: #FED049" >{{ ($gj[$i]['tj_jabatan'] != 0) ? rupiah($gj[$i]['tj_jabatan']) : '-' }}</td>
                                            <td style="background-color: #FED049" >{{ ($gj[$i]['gj_penyesuaian'] != 0) ? rupiah($gj[$i]['gj_penyesuaian']) : '-' }}</td>
                                            <td style="background-color: #FED049" >{{ ($gj[$i]['tj_perumahan'] != 0) ? rupiah($gj[$i]['tj_perumahan']) : '-' }}</td>
                                            <td style="background-color: #FED049" >{{ ($gj[$i]['tj_telepon'] != 0) ? rupiah($gj[$i]['tj_telepon']) : '-' }}</td>
                                            <td style="background-color: #FED049" >{{ ($gj[$i]['tj_pelaksana'] != 0) ? rupiah($gj[$i]['tj_pelaksana']) : '-' }}</td>
                                            <td style="background-color: #FED049" >{{ ($gj[$i]['tj_kemahalan'] != 0) ? rupiah($gj[$i]['tj_kemahalan']) : '-' }}</td>
                                            <td style="background-color: #FED049" >{{ ($gj[$i]['tj_kesejahteraan'] != 0) ? rupiah($gj[$i]['tj_kesejahteraan']) : '-' }}</td>
                                            <td>{{ ($jamsostek[$i] != 0) ?rupiah($jamsostek[$i]) : '-' }}</td>
                                            <td>-</td>
                                            <td>{{ ($gj[$i]['uang_makan'] != 0) ? rupiah($gj[$i]['uang_makan']) : '-' }}</td>
                                            <td>{{ ($gj[$i]['tj_pulsa'] != 0) ? rupiah($gj[$i]['tj_pulsa']) : '-' }}</td>
                                            <td>{{ ($gj[$i]['tj_vitamin'] != 0) ? rupiah($gj[$i]['tj_vitamin']) : '-' }}</td>
                                            <td>{{ ($gj[$i]['tj_transport'] != 0) ? rupiah($gj[$i]['tj_transport']) : '-' }}</td>
                                            @php
                                                $total_k += $gj[$i]['tj_keluarga'] + $gj[$i]['tj_jabatan'] + $gj[$i]['gj_penyesuaian'] + $gj[$i]['tj_perumahan'] + $gj[$i]['tj_telepon'] + $gj[$i]['tj_pelaksana'] + $gj[$i]['tj_kemahalan'] +$gj[$i]['tj_kesejahteraan'];
                                                $total_non += $gj[$i]['gj_pokok'] + $gj[$i]['tj_pulsa'] + $gj[$i]['tj_pulsa'] + $gj[$i]['uang_makan'];
                                        @endphp
                                    </tr>
                                    @endfor
                                </tbody>
                                <tfoot style="font-weight: bold">
                                    <tr>
                                        <td colspan="2" style="text-align: center;">
                                            Total Tunjangan + Keseluruhan
                                        </td>
                                        <td style="background-color: #FED049; text-align: center;" colspan="8">{{ rupiah($total_k) }}</td>
                                        <td style="background-color: #54B435; text-align: center;" colspan="6">{{ rupiah($total_non) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-0">
                            <h5 class="card-title mt-5">PENGHASILAN TIDAK TERATUR</h5>
                            <table class="table text-center cell-border stripe" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Bulan</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Tunjangan Uang Lembur</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Pengganti Biaya Kesehatan</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Uang Duka</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">SPD</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">SPD Pendidikan </th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">SPD Pindah Tugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_penghasilan = array();
                                    @endphp
                                    @for ($i = 0; $i < 12; $i++)
                                        <tr>
                                            <td>{{ $bulan[$i] }}</td>
                                            @for ($j = 0; $j < 6; $j++)
                                                <td>{{ ($penghasilan[$i][$j] != 0) ? rupiah($penghasilan[$i][$j]) : '-' }}</td>
                                                @php
                                                    array_push($total_penghasilan, $penghasilan[$i][$j]);
                                                @endphp
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                                <tfoot style="font-weight: bold">
                                    <tr>
                                        <td colspan="1" style="text-align: center;">
                                            Total
                                        </td>
                                        <td style="background-color: #54B435; text-align: center;" colspan="6">{{ rupiah(array_sum($total_penghasilan)) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-0">
                            <h5 class="card-title mt-5">BONUS</h5>
                            <table class="table text-center cell-border stripe" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Bulan</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Tunjangan Hari Raya</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Jasa Produksi</th>
                                        <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Dana Pendidikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_bonus =array();
                                    @endphp
                                    @for ($i = 0; $i < 12; $i++)
                                        <tr>
                                            <td>{{ $bulan[$i] }}</td>
                                            @for ($j = 0; $j < 3; $j++)
                                                <td>{{ ($bonus[$i][$j] != 0) ? rupiah($bonus[$i][$j]) : '-' }}</td>
                                                @php
                                                    array_push($total_bonus, $bonus[$i][$j]);
                                                @endphp
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                                <tfoot style="font-weight: bold">
                                    <tr>
                                        <td colspan="1" style="text-align: center;">
                                            Total
                                        </td>
                                        <td style="background-color: #54B435; text-align: center;" colspan="3">{{ rupiah(array_sum($total_bonus)) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script>
    const nipSelect = $('#nip').select2({
        ajax: {
            url: '{{ route('api.select2.karyawan') }}',
            data: function(params) {
                return {
                    search: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true,
        },
        templateResult: function(data) {
            if(data.loading) return data.text;
            return $(`
                <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
            `);
        }
    });

    nipSelect.append(`
        <option value="{{$karyawan?->nip}}">{{$karyawan?->nip}} - {{$karyawan?->nama_karyawan}}</option>
    `).trigger('change');

    // $("#table_export").DataTable({
    //     dom : "Bfrtip",
    //     iDisplayLength: -1,
    //     scrollX: true,
    // });
</script>
@endpush
