@extends('layouts.app-template')
@include('bonus.modal.remove-all')
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <div class="text-2xl font-bold tracking-tighter">
                    Bonus
                </div>
                <div class="breadcrumb">
                    <a href="/" class="text-sm text-gray-500">Dashboard</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('pajak_penghasilan.index') }}" class="text-sm text-gray-500">Penghasilan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('bonus.index') }}" class="text-sm text-gray-500">Bonus</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Edit Bonus</a>
                </div>
                <div class="button-wrapper flex gap-3">
                    <a href="{{ route('edit-tunjangan-bonus') }}?idTunjangan={{ Request()->get('idTunjangan') }}&
                                            tanggal={{ Request()->get('tanggal') }}&
                                            kdEntitas={{ Request()->get('kdEntitas') }}"
                        class="btn btn-warning-light ml-1">Import</a>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="">
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 flex gap-7" role="alert">
                    <h6 class="text-sm text-blue-900 font-semibold"> Cabang : <b>{{ $nameCabang->nama_cabang }}</b></h6> |
                    <h6 class="text-sm text-blue-900 font-semibold"> Tunjangan : <b>{{ $tunjangan->nama_tunjangan }}</b></h6>
                </div>
                <div class="table-responsive overflow-hidden content-center">
                    <form action="{{ route('edit-tunjangan-bonus-post-new') }}"
                        method="POST">
                        @csrf
                        <div class="grid lg:grid-cols-2 gap-5 items-center md:grid-cols-2 grid-cols-1 mb-3">
                            <div class="input-box">
                                <label for="selectfield">Penghasilan</label>
                                <select name="id_tunjangan_up" class="form-input" id="nip">
                                    <option value="">--- Pilih Penghasilan ---</option>
                                    @foreach ($dataTunjangan as $item)
                                    <option value="{{ $item->id }}" {{$item->id == Request()->get('idTunjangan') ? 'selected' : ''}}>{{ $item->nama_tunjangan }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="input-box">
                                <label for="datefield">Tanggal</label>
                                <input type="date" name="tanggal" value="{{$tanggal}}" id="datefield" class="form-input" required>
                            </div>
                        </div>
                        <input type="hidden" name="id_tunjangan" value="{{ Request()->get('idTunjangan') }}">
                        <input type="hidden" name="createdAt" value="{{ Request()->get('tanggal') }}">
                        <input type="hidden" name="entitas" value="{{ Request()->get('entitas') }}">
                        <input type="hidden" name="temp_nip[]" id="temp_nip">
                        <div class="flex justify-end gap-5 mb-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button id="btn-hapus" data-tunjangan="{{$tunjangan->nama_tunjangan}}" type="button" class="btn btn-danger btn-minus-all">Hapus Semua</button>
                            <button type="button" class="btn btn-danger btn-kembalikan hidden">Kembalikan</button>
                        </div>
                        <table class="tables whitespace-nowrap" id="table_item" style="width: 100%">
                            <thead class="text-primary">
                                {{-- <th>No</th> --}}
                                <th>NIP</th>
                                <th>Karyawan</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody id="t_body">
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            <input type="text" id="nominal_{{ $key }}" name="nominal[]"
                                                onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)"
                                                class="form-input"
                                                value="{{ number_format($item->nominal, 0, ',', '.') }}">
                                            <input type="hidden" name="nip[]" value="{{ $item->nip }}">
                                            <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                        </td>
                                        <td>
                                            <button id="btn-hapus" type="button" data-nip="{{$item->nip}}"
                                                class="btn btn-danger btn-minus">-</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
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

        var temp_nip_array = [];
        var temp_nip_array_all = [];

        $(document).ready(function() {
            $(`.btn-minus-all`).on('click', function(){
                const target = '#remove-all-bonus';
                const tunjangan = $(this).data("tunjangan");

                $(`${target} #tunjangan`).html(tunjangan);
                $(`${target}`).removeClass('hidden');
            })

            $(`.btn-kembalikan`).on('click', function(){
                location.reload();
            })

            $(`#remove-all-bonus`).on('click', '#hapus', function() {
                var datas = @json($data);
                $("#temp_nip").val('');
                $.each(datas, function(i, item){
                    temp_nip_array_all.push(item.nip);
                })
                $("#temp_nip").val(JSON.stringify(temp_nip_array_all));
                $(`#remove-all-bonus`).addClass('hidden');
                $(`.btn-minus-all`).addClass('hidden');
                $(`.btn-kembalikan`).removeClass('hidden');
                $('#t_body').empty();
            })

            $("#table_item").on('click', '.btn-minus', function() {
                const nip = $(this).data("nip");

                $("#temp_nip").val('');
                temp_nip_array.push(nip);

                $("#temp_nip").val(JSON.stringify(temp_nip_array));

                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
