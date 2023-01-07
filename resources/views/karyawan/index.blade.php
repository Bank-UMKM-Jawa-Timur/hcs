@extends('layouts.template')

@section('content')
      <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/karyawan">Karyawan </a></p>
        </div>
    
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('karyawan.create') }}">
                      <button class="btn btn-primary">tambah karyawan</button>
                    </a>
                    <a class="ml-3" href="{{ route('import') }}">
                      <button class="btn btn-primary">import karyawan</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                          <thead class="text-primary">
                            <th>No</th>
                            <th>
                              NIP
                            </th>
                            <th>
                              kd_entitas
                            </th>
                            <th>
                                kd_bagian
                            </th>
                            <th>
                              nama_karyawan
                            </th>
                          </thead>
                          @php
                              $num = 0;
                              $no = 1;
                          @endphp
                          <tbody>
                            @foreach ($data_pusat as $item)
                              @php
                                $jabatan = 'Pusat';
                              @endphp
                                <tr>
                                    <td>
                                      @php
                                          $num = $no++;
                                      @endphp
                                      {{ $num }}
                                    </td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->kd_entitas }}</td>
                                    <td>
                                      {{ $item->kd_bagian }}
                                    </td>
                                    <td>
                                      {{ $item->nama_karyawan }}
                                    </td>
                                </tr>
                            @endforeach
                            {{-- Foreach Data selain Pusat --}}
                            @foreach ($cabang as $item)
                                @php
                                    $data_cabang = DB::table('mst_karyawan')
                                    ->where('kd_entitas', $item->kd_cabang)
                                    ->select(
                                        'mst_karyawan.nip',
                                        'mst_karyawan.nik',
                                        'mst_karyawan.nama_karyawan',
                                        'mst_karyawan.kd_entitas',
                                        'mst_karyawan.kd_jabatan',
                                        'mst_karyawan.kd_bagian',
                                        'mst_karyawan.ket_jabatan',
                                        'mst_karyawan.status_karyawan',
                                        'mst_jabatan.nama_jabatan',
                                        'mst_karyawan.status_jabatan',
                                    )
                                    ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
                                    ->orderBy('kd_jabatan', 'desc')
                                    ->get();
                                @endphp

                                @foreach ($data_cabang as $i)
                                    <tr>
                                      <td>{{ $num++ }}</td>
                                      <td>{{ $i->nip }}</td>
                                      <td>{{ $i->kd_entitas }}</td>
                                      <td>{{ $i->kd_bagian }}</td>
                                      <td>
                                        @php
                                            $data = DB::table('mst_cabang')
                                              ->where('kd_cabang', $i->kd_entitas)
                                              ->first();

                                              if (isset($data)) {
                                                $data = $data->nama_cabang;
                                              } 
                                        @endphp
                                        {{ $i->nama_karyawan }}
                                      </td>
                                    </tr>
                                @endforeach
                            @endforeach
                          </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')

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
    $(document).ready(function() {
      $('#table thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#table thead');
        var table = $('#table').DataTable({
          dom : "Bfrtip",orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');
 
                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
 
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();
 
                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
        },
          buttons: [
              {
                  extend: 'excelHtml5',
                  text:'Excel',
                  customize: function( xlsx, row ) {
                      var sheet = xlsx.xl.worksheets['sheet1.xml'];
                  }
              }
          ]
        });
    });
  </script>
@endsection 