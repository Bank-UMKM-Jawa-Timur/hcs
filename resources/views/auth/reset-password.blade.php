<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="assets/css/style.css"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <div class="register-photo">
        <div class="form-container">
            <div class="image-holder"></div>
            <form method="POST" action="{{ route('password.reset') }}">
                @csrf
                <h2 class="text-center"><strong>Reset</strong> password.</h2>
                <input type="hidden" name="id_user" value="{{ request()->input('id') }}">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password baru"></div>
                @error('confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="form-group"><input class="form-control" type="password" name="confirmation" placeholder="Konfirmasi Password Baru"></div>
                <div class="form-group"><button class="btn btn-is-primary btn-block"  type="submit">Reset Password</button></div>
            </form>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</html>

<style>
    body{
        font-family: 'Plus Jakarta Sans', sans-serif;
        -webkit-font-smoothing: antialiased;
        background: #f1f7fc;
    }
    .register-photo {
        margin-top: 0;
        display: flex;
        justify-content: center;
        height: 100vh;
    }

    .register-photo .image-holder {
        display: table-cell;
        width: auto;
        background:url("{{ asset('style/assets/img/forgot-password.svg') }}");
        background-size: cover;
    }

    .register-photo .form-container {
        display: table;
        max-width: 900px;
        margin-top: 20vh;
        width: 100%;
        height: 50%;
        box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .register-photo form {
        display: table-cell;
        width: 400px;
        vertical-align: middle;
        background-color: #ffffff;
        padding: 40px 60px;
        color: #505e6c;
    }

    @media (max-width:991px) {
        .register-photo form {
            padding: 20px;
        }
    }

    .register-photo form h2 {
        font-size: 24px;
        line-height: 1.5;
        margin-bottom: 30px;
    }

    .register-photo form .form-control {
        background: #f7f9fc;
        border: 1px solid #e8ecf0 !important;
        border-radius: 4px;
        box-shadow: none;
        outline: none;
        font-size: 15px;
        color: inherit;
        text-indent: 6px;
        height: 40px;
    }

    .register-photo form .form-check {
        font-size: 13px;
        line-height: 20px;
    }

    .register-photo form .btn-is-primary {
        background: #0770CD;
        border: none;
        border-radius: 4px;
        padding: 11px;
        color: #fff;
        font-weight: 700;
        box-shadow: none;
        margin-top: 35px;
        text-shadow: none;
        outline: none !important;
    }

    .register-photo form .btn-is-primary:hover,
    .register-photo form .btn-is-primary:active {
        background: #0770CD;
    }

    .register-photo form .btn-is-primary:active {
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
    .btn-previous{
        background: #0770CD;
        border-radius: 50%;
        border: none;
        width: 70px;
        height: 70px;
        outline: none !important;
        position: absolute;
        left: 50%;
        font-size: 20px;
        bottom: 10rem;
        padding: 10px;
        cursor: pointer;
        color: #fff;
    }
</style>

