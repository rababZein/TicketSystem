<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="TecSee.de">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link href="{{ asset('frontend/img/brand/favicon.png') }}" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('frontend/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('frontend/css/argon.css?v=1.1.0') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('frontend/css/custom.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <header class="header-global">
            <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg navbar-transparent navbar-light headroom">
              <div class="container">
                <a class="navbar-brand mr-lg-5" href="{{ url('/') }}">
                  <img src="{{ asset('frontend/img/brand/white.png') }}" alt="brand">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse" id="navbar_global">
                  <div class="navbar-collapse-header">
                    <div class="row">
                      <div class="col-6 collapse-brand">
                        <a href="{{ url('/') }}">
                          <img src="{{ asset('frontend/img/brand/blue.png') }}" alt="brand">
                        </a>
                      </div>
                      <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                          <span></span>
                          <span></span>
                        </button>
                      </div>
                    </div>
                  </div>
                  <ul class="navbar-nav navbar-nav-hover align-items-lg-center">

                    <a href="{{ url('/') }}" class="nav-link">Home</a>

                    <li class="nav-item dropdown">
                      <a href="#" class="nav-link" data-toggle="dropdown" role="button">
                        <i class="ni ni-ui-04 d-lg-none"></i>
                        <span class="nav-link-inner--text">Features</span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-xl">
                        <div class="dropdown-menu-inner">
                          <a href="#" class="media d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-primary rounded-circle text-white">
                              <i class="ni ni-single-02"></i>
                            </div>
                            <div class="media-body ml-3">
                              <h6 class="heading text-primary mb-md-1">Admin</h6>
                              <p class="description d-none d-md-inline-block mb-0">Learn how to use Argon compiling Scss, change brand colors and more.</p>
                            </div>
                          </a>
                          <a href="#" class="media d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-success rounded-circle text-white">
                              <i class="ni ni-briefcase-24"></i>
                            </div>
                            <div class="media-body ml-3">
                              <h6 class="heading text-primary mb-md-1">Teams</h6>
                              <p class="description d-none d-md-inline-block mb-0">Learn more about colors, typography, icons and the grid system we used for Argon.</p>
                            </div>
                          </a>
                          <a href="#" class="media d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-warning rounded-circle text-white">
                              <i class="ni ni-satisfied"></i>
                            </div>
                            <div class="media-body ml-3">
                              <h5 class="heading text-warning mb-md-1">Clients</h5>
                              <p class="description d-none d-md-inline-block mb-0">Browse our 50 beautiful handcrafted components offered in the Free version.</p>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="#" class="nav-link" data-toggle="dropdown" role="button">
                        <i class="ni ni-collection d-lg-none"></i>
                        <span class="nav-link-inner--text">Links</span>
                      </a>
                      <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">About</a>
                        <a href="#" class="dropdown-item">Contact</a>

                        @guest

                            <a href="{{ url('login') }}" class="dropdown-item">Login</a>
                            <a href="{{ url('register') }}" class="dropdown-item">Register</a>

                        @else

                            <a href="{{ url('logout') }}" class="dropdown-item">Logout</a>


                        @endguest

                      </div>
                    </li>
                  </ul>
                  <ul class="navbar-nav align-items-lg-center ml-lg-auto">
                    <li class="nav-item">
                      <a class="nav-link nav-link-icon" href="#" target="_blank" data-toggle="tooltip" title="Like us on Facebook">
                        <i class="fa fa-facebook-square"></i>
                        <span class="nav-link-inner--text d-lg-none">Facebook</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link nav-link-icon" href="#" target="_blank" data-toggle="tooltip" title="Follow us on Instagram">
                        <i class="fa fa-instagram"></i>
                        <span class="nav-link-inner--text d-lg-none">Instagram</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link nav-link-icon" href="#" target="_blank" data-toggle="tooltip" title="Follow us on Twitter">
                        <i class="fa fa-twitter-square"></i>
                        <span class="nav-link-inner--text d-lg-none">Twitter</span>
                      </a>
                    </li>
                    
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                      <a href="#" class="btn btn-neutral btn-icon" data-toggle="modal" data-target="#modal-form">
                        <span class="nav-link-inner--text">Account</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </nav>

            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
              <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                  <div class="modal-content">

                      <div class="modal-body p-0">

                        <div class="card bg-secondary shadow border-0">
                          <div class="card-header bg-white pb-5">
                            <div class="text-muted text-center mb-3"><small>Sign up with</small></div>
                            <div class="text-center">
                              <a href="#" class="btn btn-neutral btn-icon mr-4">
                                <span class="btn-inner--icon">
                                  <img src="{{ asset('frontend/img/icons/common/github.svg') }}" alt="image">
                                </span>
                                <span class="btn-inner--text">Github</span>
                              </a>
                              <a href="#" class="btn btn-neutral btn-icon">
                                <span class="btn-inner--icon">
                                  <img src="{{ asset('frontend/img/icons/common/google.svg') }}" alt="image">
                                </span>
                                <span class="btn-inner--text">Google</span>
                              </a>
                            </div>
                          </div>
                          <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center text-muted mb-4">
                              <small>Or sign up with credentials</small>
                            </div>
                            <form>
                              <div class="form-group">
                                <div class="input-group input-group-alternative mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                  </div>
                                  <input class="form-control" placeholder="Name" type="text">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group input-group-alternative mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                  </div>
                                  <input class="form-control" placeholder="Email" type="email">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group input-group-alternative">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                  </div>
                                  <input class="form-control" placeholder="Password" type="password">
                                </div>
                              </div>
                              <div class="text-muted font-italic"><small>password strength: <span class="text-success font-weight-700">strong</span></small></div>
                              <div class="row my-4">
                                <div class="col-12">
                                  <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id="customCheckRegister" type="checkbox">
                                    <label class="custom-control-label" for="customCheckRegister"><span>I agree with the <a href="#">Privacy Policy</a></span></label>
                                  </div>
                                </div>
                              </div>
                              <div class="text-center">
                                <button type="button" class="btn btn-primary mt-4">Create account</button>
                              </div>
                            </form>
                          </div>
                        </div>

                      </div>

                  </div>
              </div>
          </div>

        </header><!-- End Header -->

        <main>
            @yield('content')
        </main>

        <!-- Start Footer -->
        <footer class="footer has-cards">
    
            <div class="container">
              <div class="row row-grid align-items-center my-md">
                <div class="col-lg-6">
                  <h3 class="text-primary font-weight-light mb-2">ALFERP System</h3>
                  <h4 class="mb-0 font-weight-light">What you really need.</h4>
                </div>
                <div class="col-lg-6 text-lg-center btn-wrapper">
                  <a target="_blank" href="#" class="btn btn-neutral btn-icon-only btn-twitter btn-round btn-lg" data-toggle="tooltip" data-original-title="Follow us">
                    <i class="fa fa-twitter"></i>
                  </a>
                  <a target="_blank" href="#" class="btn btn-neutral btn-icon-only btn-facebook btn-round btn-lg" data-toggle="tooltip" data-original-title="Like us">
                    <i class="fa fa-facebook-square"></i>
                  </a>
                  <a target="_blank" href="#" class="btn btn-neutral btn-icon-only btn-dribbble btn-lg btn-round" data-toggle="tooltip" data-original-title="Follow us">
                    <i class="fa fa-dribbble"></i>
                  </a>
                  <a target="_blank" href="#" class="btn btn-neutral btn-icon-only btn-github btn-round btn-lg" data-toggle="tooltip" data-original-title="Star on Github">
                    <i class="fa fa-github"></i>
                  </a>
                </div>
              </div>
              <hr>
              <div class="row align-items-center justify-content-md-between">
                <div class="col-md-6">
                  <div class="copyright">
                    &copy; 2019 <a href="https://tecsee.de/" target="_blank">TECSEE</a>.
                  </div>
                </div>
                <div class="col-md-6">
                  <ul class="nav nav-footer justify-content-end">
                    <li class="nav-item">
                      <a href="#" class="nav-link">Help</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">About Us</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">Features</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">Contact Us</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
        </footer><!-- End Footer -->

    </div> <!-- End App -->

    <!-- Core -->
    <script src="{{ asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/popper/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/headroom/headroom.min.js') }}"></script>
    <!-- Argon JS -->
    <script src="{{ asset('frontend/js/argon.js?v=1.1.0') }}"></script>

</body>
</html>
