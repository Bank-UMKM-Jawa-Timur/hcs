<div class="modal fade" id="modalUploadfile" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Upload File Lampiran Gaji</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                    <a href="#" id="cetak_lampiran_gaji" class="btn btn-outline-primary p-1 btn-lampiran-gaji" data-id="">Lampiran Gaji</a>
                <form action="{{ route('upload.penghasilanPerBulan') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="form-row mb-3">
                        <div class="col-lg-12">
                            <input type="hidden" id="id" name="id">
                            <label for="">Upload File</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload_file" class="custom-file-input" id="upload_csv" >
                                <label class="custom-file-label overflow-hidden" for="upload_csv"  style="padding: 10px 4px 30px 5px">Choose file...</label>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                {{-- <form action="{{ route('ptkp.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE') --}}
                    {{-- <input type="hidden" name="idPengajuan" value="{{$item->id}}"> --}}
                    <button type="submit" class="btn btn-danger">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
