<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Human Capital System | BANK UMKM JATIM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('style/assets/img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 ">
  @include('sweetalert::alert')
  <div class="flex w-full h-screen gap-5">
      <div class="bg-white lg:w-[40%] w-full border-r">
          <div class="form-login mt-[10vh] space-y-2">
              <div class="flex justify-center gap-4">
                  <img src="{{ asset('style/assets/img/logo.png') }}" alt="logo" class="w-[30px] mt-[5px]">
                  <div>
                  <h2 class="mt-3 font-bold text-xs tracking-tighter uppercase">  Human Capital System</h2>
                  </div>
              </div>
              <div class="text-center space-y-2 pb-3 pt-3">
                  <h2 class="font-bold tracking-tighter text-3xl text-[#1F2937]">Selamat datang! </h2>
                  <p class="text-xs text-gray-400">Silahkan masuk untuk melanjutkan!</p>
              </div>
                @if (session('success'))
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-50 dark:text-green-400 dark:border-green-200" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                      <span class="font-medium">{{ session('success') }}</span>
                    </div>
                  </div>
                @endif
              <form action="{{ route('login') }}" method="POST" class="space-y-5">
                  @csrf
                  <div class="input-box">
                      <label for="email">Email atau NIP</label>
                      <input type="text" class="border rounded-md text-sm bg-gray-50/80 border-neutral-200 px-5 py-3 w-full outline-none focus:ring-2 focus:ring-blue-300" placeholder=""
                          id="email" name="input_type" value="{{ old('input_type') }}" autofocus>
                      @error('email')
                          <span>{{ $message }}</span>
                      @enderror
                      @error('username')
                          <span>{{ $message }}</span>
                      @enderror
                  </div>
                  <div class="input-box">
                      <label for="password">Password</label>
                      <input type="password" id="password" name="password" class="border rounded-md text-sm bg-gray-50/80 border-neutral-200 px-5 py-3 w-full outline-none focus:ring-2 focus:ring-blue-300" placeholder="">
                      @error('password')
                          <span>{{ $message }}</span>
                      @enderror
                  </div>
                  <button type="submit" class="btn-login bg-theme-primary text-sm drop-shadow-lg text-white w-full">Masuk</button>
                  <div class="copyright text-center text-xs    font-semibold text-neutral-800">
                      &copy; Copyright 2022 - {{ date('Y') }} PT. BPR Jatim
                  </div>
              </form>
          </div>
      </div>
      <div class="bg-gray-50 w-full lg:block hidden">
          <img src="{{ asset('style/assets/img/login.svg') }}"  class="max-w-3xl mx-auto" alt="">
      </div>
  </div>
</body>
</html>
