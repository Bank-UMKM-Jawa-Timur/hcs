<div class="d-flex justify-content-between">
    <div>
        Showing {{$start}} to {{$obj->count() < $page_length ? ($start - 1) + $obj->count()  : $end}} of {{$obj->total()}} entries
    </div>
    <div>
        @if ($obj instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $obj->links('pagination::bootstrap-4') }}
        @endif
    </div>
</div>
