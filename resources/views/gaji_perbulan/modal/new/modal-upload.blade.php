<div class="modal-layout hidden" id="modalUploadfile" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal w-1/3" role="document">
        <div class="modal-content">
            <div class="modal-head">
                <div class="heading">
                    <h2 class="modal-title" id="confirmModalLabel">Finalisasi Proses Penggajian</h2>
                </div>
                <button type="button" class="close" data-modal-hide="modalUploadfile" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body space-y-5">
                <div class="flex">
                    <a class="ml-2 mb-3 btn btn-primary btn-download-pdf " id="download" href="" data-id="">Cetak Payroll</a>
                </div>
                <form id="form-finalisasi" action="{{ route('upload.penghasilanPerBulan') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="form-row mb-3">
                        <div class="col-lg-12">
                            <input type="hidden" id="id" name="id">
                            <div class="input-box">
                                <label for="upload_lampiran">Upload Berkas Payroll<span style="color: red;">*.pdf</span></label>
                                <div class="input-group">
                                    <input type="file" id="upload_lampiran" name="upload_file"
                                        class="custom-file-input form-upload only-pdf limit-size-10"
                                        accept="application/pdf" required>
                                    <button class="upload-group-icon">
                                        <label for="upload">
                                            <i class="ti ti-upload"></i>
                                        </label>
                                    </button>
                                </div>
                                <span class="text-red-500 m-0 error-msg" style="display: none"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer to-right">
                <button type="button" class="btn btn-light" data-modal-dismiss="modalUploadfile">Batal</button>
                    <button type="button" class="btn btn-primary btn-final">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        $('#modalUploadfile .close').on('click', function() {
            $('#modalUploadfile').addClass('hidden')
            $('#modalUploadfile #upload_lampiran').val('')
        })
    </script>
@endpush
