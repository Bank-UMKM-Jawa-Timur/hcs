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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
@include('sweetalert::alert')
<body class="bg-gray-50">
    <div class="flex w-full h-screen gap-5">
        <div class="bg-white lg:w-[40%] w-full border-r">
           <div class="form-login mt-[10vh] space-y-2">
            @if (Session::has('status'))
            <div class="border border-theme-primary text-theme-primary bg-theme-primary/10 text-center" role="alert">
                {{ Session::get('message') }}
            </div>
         @endif
            <div class="flex justify-center gap-4">
                <img src="{{ asset('style/assets/img/logo.png') }}" alt="logo" class="w-[30px] mt-[5px]">
                <div>
                  <h2 class="mt-3 font-bold text-xs tracking-tighter uppercase">  Human Capital System</h2>
                </div>
                <div class="text-center space-y-2 pb-3 pt-3">
                    <h2 class="font-bold tracking-tighter text-3xl text-[#1F2937]">Selamat datang! </h2>
                    <p class="text-xs text-gray-400">Silahkan masuk untuk melanjutkan!</p>
                </div>
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="input-box">
                        <label for="email">Email atau NIP</label>
                        <input type="text" class="form-input" placeholder=""
                            id="email" name="input_type" value="{{ old('input_type') }}">
                        @error('email')
                            <span>{{ $message }}</span>
                        @enderror
                        @error('username')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="">
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
            <div class="text-center space-y-2 pb-3 pt-3">
                <h2 class="font-bold tracking-tighter text-3xl text-[#1F2937]">Selamat datang! </h2>
                <p class="text-xs text-gray-400">Silahkan masuk untuk melanjutkan!</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div class="input-box">
                    <label for="">Email atau NIP</label>
                    <input type="text" class="form-input @error('input_type') border border-theme-primary @enderror"  name="input_type" value="{{ old('input_type') }}" required autocomplete="email" autofocus>
                    @if ($errors->get('email'))
                    <span class="text-theme-primary">{{ $errors->get('email')[0] }}</span>
                @endif
                @if ($errors->get('username'))
                    <span class="text-theme-primary">{{ $errors->get('username')[0] }}</span>
                @endif
                @error('input_type')
                    <span class="text-theme-primary" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>
                <div class="input-box">
                    <label for="">Password</label>
                    <input type="password" class="form-input @error('password') border border-theme-primary @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="text-theme-primary" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
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