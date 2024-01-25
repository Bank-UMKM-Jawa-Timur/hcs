<div class="modal-layout hidden" id="restore-modal" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="heading">
                <h2>Konfirmasi</h2>
            </div>
            <button data-modal-dismiss="restore-modal"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <form action="{{route('restore-proses-gaji', 1)}}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <h2>Apakah anda yakin ingin kembalikan proses penggajian kantor <span id="kantor"><b></b></span>, bulan <span id="bulan"><b></b></span>, tahun <span id="tahun"><b></b></span>?</h2>
            </div>
            <div class="modal-footer to-right">
                <button data-modal-dismiss="restore-modal" class="btn btn-light" type="button">Batal</button>
                <button class="btn btn-primary" type="submit">Kembalikan</button>
            </div>
        </form>
    </div>
</div>
