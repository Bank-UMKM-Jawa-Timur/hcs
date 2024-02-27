@extends('layouts.app-template')
@include('database.modal')


@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Database</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Database</a>
                </div>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
            @if (count($database->rollbacks) > 0)
                <button class="btn btn-primary mb-3" id="modal_rollback">Rollback Database</button>
            @endif
            <table class="table whitespace-nowrap" id="backup-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Backup</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($database->backups as $backup)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $backup['name'] }}</td>
                            <td>{{ $backup['time']->format('d M Y - H:i') }}</td>
                            <td>
                                @if ($database->position['name'] != substr($backup['name'], '0', '-4'))
                                    <a href="{{ route('database.restore', $backup['id']) }}"
                                        class="btn btn-sm btn-primary btn-restore">
                                        Restore
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @isset($database->position['type'])
            <div class="table-wrapping">
                <div class="card-body">
                    <h6 class="mb-4">Informasi Database</h6>
                    <ul class="list-group">
                        <li class="list-group-item">
                            @php
                                $type = $database->position['type'];
                                $stClass = $type == 'backups' ? 'primary' : 'success';
                                $stWord = $type == 'backups' ? 'backup' : 'rollback';
                            @endphp

                            Status: <span class="badge badge-{{ $stClass }}">{{ $stWord }}</span>
                        </li>
                        <li class="list-group-item">
                            Nama: <small class="text-muted">{{ $database->position['name'] }}</small>
                        </li>
                        <div class="list-group-item text-center">
                            <form action="{{ route('database.checkout') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" id="checkout-btn">Checkout</button>
                            </form>
                        </div>
                    </ul>
                </div>
            </div>
        @endisset
    </div>

@endsection

@push('extraScript')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#backup-table').DataTable();

        $('#backup-table').on('click', '.btn-restore', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                    icon: 'question',
                    title: 'Apakah anda yakin?',
                    text: 'Aksi akan mereset semua data aplikasi berdasarkan backup terpilih',
                })
                .then((result) => {
                    if (result.isConfirmed) window.location.href = url;
                });
        });

        $('#checkout-btn').click(function(e) {
            e.preventDefault();
            const form = $(this).parent();

            Swal.fire({
                    icon: 'question',
                    title: 'Apakah anda yakin?',
                    text: 'Aksi akan melakukan checkout permanen pada database',
                })
                .then((result) => {
                    if (result.isConfirmed) form.submit();
                });
        });
    </script>
    <script>
        $('#modal_rollback').on('click', function(e) {
        console.log("masuk");
            $(`#rollback-modal`).removeClass('hidden')
        })
    </script>
@endpush
