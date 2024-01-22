<div class="showing">
    Showing {{ $start }} {{$obj->count() < $page_length ? ($start - 1) + $obj->count()  : $end}} of {{$obj->total()}} entries
</div>
<div>
    @if ($obj instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $obj->links('pagination::tailwind') }}
    @endif
</div>