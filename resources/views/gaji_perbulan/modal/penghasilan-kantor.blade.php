<div class="modal" tabindex="-1" id="penghasilan-kantor-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penghasilan Semua Kantor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember')
                @endphp
                <div class="table-responsive">
                    <table class="display" id="penghasilan-kantor-table">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2">No</th>
                                <th class="text-center" rowspan="2">Kantor</th>
                                <th class="text-center" colspan="{{count($months)}}">Periode {{date('Y')}}</th>
                            </tr>
                            <tr>
                                @foreach ($months as $item)
                                    <th class="text-center">{{$item}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>