{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password
        reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="register-photo">
        <div class="form-container">
            <div class="image-holder"></div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <h2 class="text-center"><strong>Update</strong> password.</h2>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @error('old_pass')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="form-group"><input class="form-control" type="password" name="old_pass" placeholder="Password lama"></div>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password baru"></div>
                @error('confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="form-group"><input class="form-control" type="password" name="confirmation" placeholder="Konfirmasi Password Baru"></div>
                <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Update Password</button></div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<style>
    .register-photo {
        background: #f1f7fc;
        margin-top: 0;
        display: flex;
        justify-content: center;
        height: 100vh;
    }

    .register-photo .image-holder {
        display: table-cell;
        width: auto;
        background:url("{{ asset('style/assets/img/change-passoword-img.jpg') }}");
        background-size: cover;
    }

    .register-photo .form-container {
        display: table;
        max-width: 900px;
        margin-top: 25vh;
        width: 100%;
        height: 50%;
        box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .register-photo form {
        display: table-cell;
        width: 400px;
        background-color: #ffffff;
        padding: 40px 60px;
        color: #505e6c;
    }

    @media (max-width:991px) {
        .register-photo form {
            padding: 40px;
        }
    }

    .register-photo form h2 {
        font-size: 24px;
        line-height: 1.5;
        margin-bottom: 30px;
    }

    .register-photo form .form-control {
        background: #f7f9fc;
        border: none;
        border-bottom: 1px solid #dfe7f1;
        border-radius: 0;
        box-shadow: none;
        outline: none;
        color: inherit;
        text-indent: 6px;
        height: 40px;
    }

    .register-photo form .form-check {
        font-size: 13px;
        line-height: 20px;
    }

    .register-photo form .btn-primary {
        background: #f4476b;
        border: none;
        border-radius: 4px;
        padding: 11px;
        box-shadow: none;
        margin-top: 35px;
        text-shadow: none;
        outline: none !important;
    }

    .register-photo form .btn-primary:hover,
    .register-photo form .btn-primary:active {
        background: #eb3b60;
    }

    .register-photo form .btn-primary:active {
        transform: translateY(1px);
    }

    .register-photo form .already {
        display: block;
        text-align: center;
        font-size: 12px;
        color: #6f7a85;
        opacity: 0.9;
        text-decoration: none;
    }
</style>