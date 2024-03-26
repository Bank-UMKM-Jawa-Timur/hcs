<div class="modal-layout hidden" tabindex="-1" id="penghasilan-kantor-modal">
    <div class="modal w-full my-3">
        <div class="modal-content">
            <div class="modal-head">
               <div class="heading">
                <h2 class="modal-title">Penghasilan Semua Kantor</h2>
               </div>
                <button type="button" class="close" data-modal-dismiss="modal" data-modal-hide="penghasilan-kantor-modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember')
                @endphp
                <div class="table-wrapping">
                    <table class="tables display table-stripped" id="penghasilan-kantor-table">
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
