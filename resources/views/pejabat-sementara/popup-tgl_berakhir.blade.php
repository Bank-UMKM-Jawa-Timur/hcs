@foreach ($pjs as $item)
    @if (!$item->tanggal_berakhir)
        <div class="modal fade" id="exampleModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <form action="{{ route('pejabat-sementara.destroy', $item->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title fs-5" id="exampleModalLabel">Penonaktifan pejabat sementara</h3>
                    </div>
                    <div class="modal-body">
                        <label for="tgl_berakhir">Tanggal Berakhir</label>
                        <input type="date" class="form-control" name="tgl_berakhir">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" style="min-width: 60px;">
                            nonaktifkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
@endforeach