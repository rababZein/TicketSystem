
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="hold-transition register-page">
<div class="register-box" id="app">
  <div class="register-logo">
    <a href="#">ALFerp</a>
  </div>
  <!-- /.login-logo -->
  @yield('content')
</div>
<!-- /.login-box -->

</body>
</html>
