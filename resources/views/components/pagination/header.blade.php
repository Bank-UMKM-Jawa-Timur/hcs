<div class="layout-component">
    <div class="shorty-table">
        <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
        <select name="page_length" id="page_length"
            class="border px-4 py-2 cursor-pointer rounded appearance-none text-center">
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
        <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
    </div>
    <div class="input-search">
        <i class="ti ti-search"></i>
        <input type="search" name="q" id="q" placeholder="Cari disini..."
            class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
    </div>
</div>
