<div class="modal-layout no-backdrop-click hidden" id="modalUploadfile" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-head">
                <div class="heading">
                    <h2 class="modal-title" id="confirmModalLabel">Upload File Lampiran Gaji</h2>
                </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
            </div>
            <div class="modal-body space-y-5">
                <div class="flex">
                    <a class="ml-2 mb-3 btn btn-primary btn-download-pdf " id="download" href="" data-id="">Lampiran Gaji</a>
                    {{--  <a href="#" id="cetak_lampiran_gaji" class="btn btn-primary btn-lampiran-gaji" data-id="">Lampiran Gaji</a>  --}}
                </div>
                <form action="{{ route('upload.penghasilanPerBulan') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="form-row mb-3">
                        <div class="col-lg-12">
                            <input type="hidden" id="id" name="id">
                            <div class="input-box">
                                <label for="upload_csv">Upload File</label>
                                <div class="input-group">
                                    <input type="file" id="upload_csv" name="upload_file" class="custom-file-input form-upload">
                                    <button class="upload-group-icon">
                                        <label for="upload">
                                            <i class="ti ti-upload"></i>
                                        </label>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer to-right">
                <button type="button" class="btn btn-light" data-modal-dismiss="modalUploadfile">Batal</button>
                {{-- <form action="{{ route('ptkp.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE') --}}
                    {{-- <input type="hidden" name="idPengajuan" value="{{$item->id}}"> --}}
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
