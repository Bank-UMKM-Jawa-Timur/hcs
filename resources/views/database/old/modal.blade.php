<div class="modal" tabindex="-1" id="rollback-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rollback Database</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                                <a href="{{ route('database.rollback', $rollback['id']) }}" class="btn btn-sm btn-primary btn-backup">
                                    Rollback
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $('#rollback-table').DataTable();
</script>
@endpush
