<div class="modal-layout hidden" id="delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="heading">
                <h2>Konfirmasi</h2>
            </div>
            <button data-modal-hide="delete-modal" data-modal-dismiss="modal" class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <form action="{{route('delete-proses-gaji', 1)}}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <h2>Apakah anda yakin ingin menghapus proses penggajian Kantor <b><span id="kantor"></span></b> periode <b><span id="tahun"></span> <span id="bulan"></span></b>?</h2>
            </div>
            <div class="modal-footer to-right">
                <button data-modal-hide="delete-modal" data-modal-dismiss="modal" class="btn btn-light" type="button">Batal</button>
                <button class="btn btn-danger" type="submit">Hapus</button>
            </div>
        </form>
    </div>
</div>
