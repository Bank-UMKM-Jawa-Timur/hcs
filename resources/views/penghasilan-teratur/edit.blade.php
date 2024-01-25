@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data Penghasilan Teratur</h2>
                <div class="breadcrumb">
                    <a href="/" class="text-sm text-gray-500 font-bold">Dashboard</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('penghasilan.import-penghasilan-teratur.index') }}"
                        class="text-sm text-gray-500 font-bold">Penghasilan Teratur </a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500">Edit</p>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                <a href="{{ route('penghasilan.edit-tunjangan-import') }}?idTunjangan={{ Request()->get('idTunjangan') }}&bulan={{ Request()->get('bulan') }}&tanggal={{ Request()->get('tanggal') }}&createdAt={{ Request()->get('createdAt') }}&entitas={{ Request()->get('entitas') }}"
                    class="btn btn-warning-light">Import</a>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">

            {{-- <h6 class="text-lg text-neutral-600"> Tunjangan : {{$tunjangan->nama_tunjangan}}</h6> --}}
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 flex gap-7" role="alert">
                <h6 class="text-sm text-blue-900 font-semibold"> Cabang : <b>{{ $nameCabang->nama_cabang ?? '-' }}</b>
                </h6> |
                <h6 class="text-sm text-blue-900 font-semibold"> Tunjangan : <b>{{ $tunjangan->nama_tunjangan }}</b>
                </h6>
            </div>
            <input type="hidden" name="tanggal" value="{{ \Request::get('tanggal') }}">
            <input type="hidden" name="createdAt" value="{{ \Request::get('createdAt') }}">
            <input type="hidden" name="kdEntitas" value="{{ \Request::get('kdEntitas') }}">
            <form action="{{ route('penghasilan.edit-tunjangan-new') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary mb-2">Simpan</button>
                <table class="tables" id="table_item">
                    <thead>
                        {{-- <th>No</th> --}}
                        <th>NIP</th>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($data as $key => $item)
                            <tr>
                                {{-- <td>{{ $loop->iteration }}</td> --}}
                                <td>{{ $item->nip_tunjangan }}</td>
                                <td>{{ $item->nama_karyawan }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                <td>
                                    <input type="text" id="nominal_{{ $key }}" name="nominal[]"
                                        onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)"
                                        class="form-input" value="{{ number_format($item->nominal, 0, ',', '.') }}">
                                        <input type="hidden" name="id_tunjangan" value="{{$tunjangan->id}}">
                                        <input type="hidden" name="tanggal" value="{{Request()->get('tanggal')}}">
                                        <input type="hidden" name="createdAt" value="{{Request()->get('createdAt')}}">
                                        <input type="hidden" name="nip[]" value="{{$item->nip_tunjangan}}">
                                        <input type="hidden" name="item_id[]" value="{{$item->id_tunjangan}}">
                                </td>
                                <td>
                                    <button id="btn-hapus" class="btn btn-danger btn-minus">-</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="5">Data Kosong</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
            {{-- <div class="table-footer">
                    <div class="showing">
                        Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                    </div>
                    <div class="pagination">
                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::tailwind', [
                                'tanggal' => \Request::get('tanggal'),
                                'createdAt' => \Request::get('createdAt'),
                            ]) }}
                        @endif
                    </div>
                </div> --}}
        </div>
    </div>
@endsection

@push('extraScript')
    <script>
        $('#page_length').on('change', function() {
            $('#form').submit()
        })
        // Adjust pagination url
        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
            if (page_url.includes('tanggal')) {
                var tanggal = "{{ \Request::get('tanggal') }}"
                btn_pagination[i].href += `&tanggal=${tanggal}`
            }
            if (page_url.includes('createdAt')) {
                var createdAt = "{{ \Request::get('createdAt') }}"
                btn_pagination[i].href += `&createdAt=${createdAt}`
            }
            if (page_url.includes('kdEntitas')) {
                var kdEntitas = "{{ \Request::get('kdEntitas') }}"
                btn_pagination[i].href += `&kdEntitas=${kdEntitas}`
            }
        })

        function formatRupiahTwo(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }


        function inputFormatRupiah(id) {
            $('#' + id).on('input', function() {
                var inputValue = $(this).val().replace(/\D/g, "");
                var formattedValue = formatRupiahTwo(inputValue);
                $(this).val(formattedValue);
            });
        }

        $("#table_item").on('click', '.btn-minus', function() {
            $(this).closest('tr').remove();
        })
    </script>
@endpush
