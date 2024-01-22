<div class="w-full bg-white border-b top-0 z-30 sticky">
<div class="flex flex-nowrap justify-between items-center">
    <div>
        <button class="lg:hidden flex items-center gap-3 ml-3 toggle-menu">
          <i class="ti ti-layout-sidebar-right-collapse text-4xl"></i>
          <span>
            <p class="text-[15px] font-semibold"></p>
          </span>
        </button>
      </div>
    <div class="w-full flex p-[11px] ">
        <span class="lg:flex hidden border flex-nowrap flex-none justify-start rounded-lg">
            <button class="bg-theme-primary px-7 py-2 items-center flex gap-3 rounded-md font-semibold text-white ">
                <i class="ti ti-calendar-month text-2xl"></i>
                <p class="text-[15px]" id="date"></p>
            </button>
            <button class=" px-7 py-2 flex items-center justify-left gap-3 rounded-tr-md rounded-br-md font-semibold">
                <i class="ti ti-clock text-theme-primary text-2xl"></i>
                <p class="text-[15px]" id="clock">15:10</p>
            </button>
        </span>
    </div>
    <div class="w-56 cursor-pointer dropdown-account p-[11px] lg:border-l">
        <div class="flex lg:justify-center justify-end">
            <img src="https://ui-avatars.com/api/?background=0770CD&color=FFFFFF&name={{ Auth::guard('karyawan')->check() ? auth()->user()->nama_karyawan : auth()->user()->name }}" class="w-[45px] h-[45px] rounded-full" alt="">
            <div class="ml-3 mt-1 lg:block hidden">
                <h3 class="text-xs text-gray-400 font-semibold">Selamat datang</h3>
                <h2 class="font-bold text-neutral-800 tracking-tighter text-nowrap">{{ Auth::guard('karyawan')->check() ? auth()->user()->nama_karyawan : auth()->user()->name }}</h2>
            </div>
        </div>
    <div class="dropdown-account-menu hidden">
        <ul class="">
            <li>
            <a href="{{ route('password.request') }}">
                <button class="item-dropdown">Ganti Password</button>
            </a>
            </li>
            <li>
            <a href="{{ route('logout') }}">
                <button data-modal-id="modal-logout"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
                class="open-modal item-dropdown border-t flex gap-3">
                <span class="mt-[2px]"
                    ><iconify-icon icon="tabler:logout"></iconify-icon
                ></span>
                <span>Logout</span>
                </button>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST"
            class="d-none">
            @csrf
        </form>

            </li>
        </ul>
        </div>
    </div>
</div>
</div>