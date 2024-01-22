{{-- modal reset --}}
<div class="modal-layout hidden" id="confirmResetModal" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="heading">
                <h2>Konfirmasi Reset Session</h2>
            </div>
            <button type="button" data-modal-dismiss="confirmResetModal"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <div class="modal-body text-left">
            <h2>Apakah Anda Yakin Ingin Reset Session Ini</b>?</h2>
        </div>
        <div class="modal-footer to-right">
            <button type="button" data-modal-dismiss="confirmResetModal" class="btn btn-light" type="button">Batal</button>
            <form action="{{ route('reset-sessions.reset') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" name="id" id="idReset" value="">
                    <button data-modal-dismiss="confirmResetModal" class="btn btn-primary" type="submit">Reset</button>
            </form>
        </div>
    </div>
</div>