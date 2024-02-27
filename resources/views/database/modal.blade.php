<div class="modal-layout hidden" id="rollback-modal" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="heading">
                <h2>Rollback Database</h2>
            </div>
            <button data-modal-dismiss="rollback-modal" class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <div class="modal-body">
            <table class="table whitespace-nowrap" id="rollback-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Rollback</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($database->rollbacks as $rollback)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rollback['name'] }}</td>
                            <td>{{ $rollback['time']->format('d M Y - H:i') }}</td>
                            <td>
                                <a href="{{ route('database.rollback', $rollback['id']) }}"
                                    class="btn btn-sm btn-primary btn-backup">
                                    Rollback
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer to-right">
            <button data-modal-dismiss="rollback-modal" class="btn btn-light" type="button">Tutup</button>
        </div>
    </div>
</div>


@push('extraScript')
    <script>
        $('#rollback-table').DataTable();
    </script>
@endpush
