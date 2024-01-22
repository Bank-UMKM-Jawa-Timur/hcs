<div class="layout-component">
    <div class="shorty-table">
        <label for="page_length">Show</label>
        <select name="page_length" class="mr-3 text-sm text-neutral-400 page_length" id="page_length">
            <option value="10"
                @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                10</option>
            <option value="20"
                @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                20</option>
            <option value="50"
                @isset($_GET['page_length']) {{ $_GET['page_length'] == 50 ? 'selected' : '' }} @endisset>
                50</option>
            <option value="100"
                @isset($_GET['page_length']) {{ $_GET['page_length'] == 100 ? 'selected' : '' }} @endisset>
                100</option>
        </select>
        <label for="page_length">entries</label>
    </div>
    <div class="input-search">
        <i class="ti ti-search"></i>
        <input type="search" placeholder="Search" name="q" id="q"
            value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
    </div>
</div>
