<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('style/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('style/assets/img/logo.png') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Human Capital System | BANK UMKM JATIM
  </title>  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
  
  <link rel="stylesheet" href="{{ asset('style/assets/css/login.css.map') }}"/>
  <link rel="stylesheet" href="{{ asset('style/assets/css/login.css') }}"/>
</head>
<body>
  @include('sweetalert::alert')
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container all-card">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="{{ asset('style/assets/img/Security-amico.png') }}" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body text-center">
              <div class="brand-wrapper">
                <img src="{{ asset('style/assets/img/logo.png') }}" alt="logo" class="logo">
              </div>
              <p class="login-card-description">Welcome !</p>
              <p class="login-card-sub-description">Human Capital System</p>
                <div class="container">
                  <div class="row justify-content-center align-items-center">
                    <div class="formulir col-6 col-md-7 col-6">
                      <form class="form-group" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                          <div class="col">
                            @if (Session::has('status'))
                                <div class="alert alert-danger h-0 p-1" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                            <div class="form-group">
                              <label for="email" class="sr-only">Email</label>
                              <input autofocus placeholder="Masukkan Email atau NIP" id="email" type="text" class="form-control @error('input_type') is-invalid @enderror" name="input_type" value="{{ old('input_type') }}" required autocomplete="email" autofocus>
                              @if ($errors->get('email'))
                                  <span class="text-theme-primary">{{ $errors->get('email')[0] }}</span>
                              @endif
                              @if ($errors->get('username'))
                                  <span class="text-theme-primary">{{ $errors->get('username')[0] }}</span>
                              @endif
                              @error('input_type')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror --}}
                            </div>
                            <div class="form-group">
                              <label for="password" class="sr-only">Password</label>
                              <input id="password" placeholder="Masukkan password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                              {{-- @error('password')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror --}}
                            </div>
                            <div class="custom-checkbox col-6">
                              <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                              <label class="custom-control-label" for="remember">Remember me</label>
                            </div>
                          </div>
                        </div>
                        <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                    </form>
                    </div>
                  </div>
                </div>
                <nav class="login-card-footer-nav">
                  <span class="copyright">
                    © 2022 PT. BPR Jatim
                  </span>
                </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>