<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('style/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('style/assets/img/logo.png') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Bio Interface | BANK UMKM JATIM
  </title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="{{ asset('style/assets/css/login.css.map') }}"/>
  <link rel="stylesheet" href="{{ asset('style/assets/css/login.css') }}"/>
</head>
<body>
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
              <p class="login-card-sub-description">BIO INTERFACE</p>
                <div class="container">
                  <div class="row justify-content-center align-items-center">
                    <div class="formulir col-6 col-md-7 col-6">
                      <form class="form-group" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                              <input autofocus placeholder="Masukkan email" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                              @error('email')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                            </div>
                            <div class="form-group">
                              <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
                              <input id="password" placeholder="Masukkan password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                              @error('password')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
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
                {{-- <a href="#!" class="forgot-password-link">Forgot password?</a>
                <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p> --}}
                <nav class="login-card-footer-nav">
                  <span class="copyright">
                    © <script>
                      document.write(new Date().getFullYear())
                    </script>
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
