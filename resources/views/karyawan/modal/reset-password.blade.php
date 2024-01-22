<div class="modal-layout" id="modal-confirm-${formId}" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <h2>
                Konfirmasi Reset Password
            </h2>
            <button data-modal-dismiss="modal-confirm-${formId}"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <div class="modal-body">
            <p class="text-left">Apakah Anda yakin ingin mereset password pengguna, 
            <b>${formname}</b> dengan nip, <b>${formId}</b>?</p>
        </div>
        <div class="modal-footer to-right">
            <button data-modal-dismiss="modal-confirm-${formId}" class="btn btn-light" type="button">Cancel</button>
            <form id="form-reset" action="{{ route('reset-password-karyawan') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="formId" value="${formId}">
                <button type="submit" class="btn btn-primary">Reset</button>
            </form>
        </div>
    </div>
</div>