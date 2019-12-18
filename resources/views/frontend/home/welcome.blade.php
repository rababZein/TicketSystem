@extends('frontend.layouts.app')

@section('content')

	<main>
		<div class="position-relative">
		  <!-- shape Hero -->
		  <section class="section section-lg section-shaped pb-250">
		    <div class="shape shape-style-1 shape-default">
		      
		    </div>
		    <div class="container py-lg-md d-flex">
		      <div class="col px-0">
		        <div class="row">
		          <div class="col-lg-6">
		            <h1 class="display-3  text-white">The Perfect System<span>What you really need</span></h1>
		            <p class="lead  text-white">ALFERP system ( full control - statistics and reports - track clients projects - real time agents working hours - more features.. )</p>
		            <div class="btn-wrapper">
		              <a href="#" class="btn btn-info btn-icon mb-3 mb-sm-0">
		                <span class="btn-inner--icon"><i class="ni ni-check-bold"></i></span>
		                <span class="btn-inner--text">Try it!</span>
		              </a>
		              <a href="#" class="btn btn-white btn-icon mb-3 mb-sm-0">
		                <span class="btn-inner--icon"><i class="ni ni-cart"></i></span>
		                <span class="btn-inner--text">Buy Now</span>
		              </a>
		            </div>
		          </div>

		          <div class="col-lg-6 mb-lg-auto">
		            <div class="rounded shadow-lg overflow-hidden transform-perspective-right">
		              <div id="carousel_example" class="carousel slide" data-ride="carousel">
		                <ol class="carousel-indicators">
		                  <li data-target="#carousel_example" data-slide-to="0" class="active"></li>
		                  <li data-target="#carousel_example" data-slide-to="1"></li>
		                  <li data-target="#carousel_example" data-slide-to="2"></li>
		                </ol>
		                <div class="carousel-inner">
		                  <div class="carousel-item active">
		                    <img class="img-fluid" src="{{ asset('frontend/img/slider/1.png') }}" alt="First slide">
		                  </div>
		                  <div class="carousel-item">
		                    <img class="img-fluid" src="{{ asset('frontend/img/slider/2.png') }}" alt="Second slide">
		                  </div>
		                  <div class="carousel-item">
		                    <img class="img-fluid" src="{{ asset('frontend/img/slider/3.png') }}" alt="Second slide">
		                  </div>
		                </div>
		                <a class="carousel-control-prev" href="#carousel_example" role="button" data-slide="prev">
		                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		                  <span class="sr-only">Previous</span>
		                </a>
		                <a class="carousel-control-next" href="#carousel_example" role="button" data-slide="next">
		                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
		                  <span class="sr-only">Next</span>
		                </a>
		              </div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </div>
		    
		  </section>
		  <!-- 1st Hero Variation -->
		</div>
		<section class="section section-lg pt-lg-0 mt--200">
		  <div class="container">
		    <div class="row justify-content-center">
		      <div class="col-lg-12">
		        <div class="row row-grid">
		          <div class="col-lg-4">
		            <div class="card card-lift--hover shadow border-0">
		              <div class="card-body py-5">
		                <div class="icon icon-shape icon-shape-primary rounded-circle mb-4">
		                  <i class="ni ni-check-bold"></i>
		                </div>
		                <h6 class="text-primary text-uppercase">Free Package</h6>
		                <p class="description mt-3">ALFERP system free package allow you fully test the system with all permissions and controll.</p>
		                <div>
		                  <span class="badge badge-pill badge-primary">Free</span>
		                  <span class="badge badge-pill badge-primary">Package</span>
		                  <span class="badge badge-pill badge-primary">ALFERP</span>
		                </div>
		                <a href="#" class="btn btn-primary mt-4">Subscribe</a>
		              </div>
		            </div>
		          </div>
		          <div class="col-lg-4">
		            <div class="card card-lift--hover shadow border-0">
		              <div class="card-body py-5">
		                <div class="icon icon-shape icon-shape-success rounded-circle mb-4">
		                  <i class="ni ni-diamond"></i>
		                </div>
		                <h6 class="text-success text-uppercase">Diamond Package</h6>
		                <p class="description mt-3">ALFERP system diamond package allow you fully test the system with all permissions and controll.</p>
		                <div>
		                  <span class="badge badge-pill badge-success">diamond</span>
		                  <span class="badge badge-pill badge-success">package</span>
		                  <span class="badge badge-pill badge-success">ALFERP</span>
		                </div>
		                <a href="#" class="btn btn-success mt-4">Subscribe</a>
		              </div>
		            </div>
		          </div>
		          <div class="col-lg-4">
		            <div class="card card-lift--hover shadow border-0">
		              <div class="card-body py-5">
		                <div class="icon icon-shape icon-shape-warning rounded-circle mb-4">
		                  <i class="ni ni-diamond"></i>
		                </div>
		                <h6 class="text-warning text-uppercase">Diamond Plus</h6>
		                <p class="description mt-3">ALFERP system diamond plus package allow you fully test the system with all permissions and controll.</p>
		                <div>
		                  <span class="badge badge-pill badge-warning">diamond</span>
		                  <span class="badge badge-pill badge-warning">package</span>
		                  <span class="badge badge-pill badge-warning">ALFERP</span>
		                </div>
		                <a href="#" class="btn btn-warning mt-4">Subscribe</a>
		              </div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>
		</section>
		<section class="section section-lg">
		  <div class="container">
		    <div class="row row-grid align-items-center">
		      <div class="col-md-6 order-md-2">
		        <img src="{{ asset('frontend/img/theme/promo-1.png') }}" class="img-fluid floating" alt="image">
		      </div>
		      <div class="col-md-6 order-md-1">
		        <div class="pr-md-5">
		          <div class="icon icon-lg icon-shape icon-shape-success shadow rounded-circle mb-5">
		            <i class="ni ni-like-2"></i>
		          </div>
		          <h3>ALFERP Features</h3>
		          <p>The kit comes with three pre-built pages to help you get started faster. You can change the text and images and you're good to go.</p>
		          <ul class="list-unstyled mt-5">
		            <li class="py-2">
		              <div class="d-flex align-items-center">
		                <div>
		                  <div class="badge badge-circle badge-success mr-3">
		                    <i class="ni ni-settings-gear-65"></i>
		                  </div>
		                </div>
		                <div>
		                  <h6 class="mb-0">Carefully crafted components</h6>
		                </div>
		              </div>
		            </li>
		            <li class="py-2">
		              <div class="d-flex align-items-center">
		                <div>
		                  <div class="badge badge-circle badge-success mr-3">
		                    <i class="ni ni-html5"></i>
		                  </div>
		                </div>
		                <div>
		                  <h6 class="mb-0">Amazing page examples</h6>
		                </div>
		              </div>
		            </li>
		            <li class="py-2">
		              <div class="d-flex align-items-center">
		                <div>
		                  <div class="badge badge-circle badge-success mr-3">
		                    <i class="ni ni-satisfied"></i>
		                  </div>
		                </div>
		                <div>
		                  <h6 class="mb-0">Super friendly support team</h6>
		                </div>
		              </div>
		            </li>
		          </ul>
		        </div>
		      </div>
		    </div>
		  </div>
		</section>

		<section class="section pb-0 bg-gradient-warning">
		  <div class="container">

		    <div class="row justify-content-center text-center mb-lg">
		      <div class="col-lg-8">
		        <h2 class="display-3">Our Clients</h2>
		        <p class="lead text-muted">According to the National Oceanic and Atmospheric Administration, Ted, Scambos, NSIDClead scentist, puts the potentially record maximum.</p>
		      </div>
		    </div>

		    <div class="row">
		      <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
		        <div class="px-4">
		          <img src="{{ asset('frontend/img/clients/1.png') }}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width: 200px;" alt="image">
		          <div class="pt-4 text-center">
		            <h5 class="title">
		              <span class="d-block mb-1">Brand One</span>
		            </h5>
		            
		          </div>
		        </div>
		      </div>
		      <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
		        <div class="px-4">
		          <img src="{{ asset('frontend/img/clients/2.png') }}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width: 200px;" alt="image">
		          <div class="pt-4 text-center">
		            <h5 class="title">
		              <span class="d-block mb-1">Brand Two</span>
		            </h5>
		            
		          </div>
		        </div>
		      </div>
		      <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
		        <div class="px-4">
		          <img alt="image" src="{{ asset('frontend/img/clients/3.png') }}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width: 200px;">
		          <div class="pt-4 text-center">
		            <h5 class="title">
		              <span class="d-block mb-1">Brand Three</span>
		            </h5>
		            
		          </div>
		        </div>
		      </div>
		      <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
		        <div class="px-4">
		          <img alt="image" src="{{ asset('frontend/img/clients/4.png') }}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width: 200px;">
		          <div class="pt-4 text-center">
		            <h5 class="title">
		              <span class="d-block mb-1">Brand Four</span>
		            </h5>
		            
		          </div>
		        </div>
		      </div>

		    </div>
		  </div>
		  
		</section>


		<section class="section section-lg bg-gradient-default">
		  <div class="container pt-lg pb-300">
		    <div class="row text-center justify-content-center">
		      <div class="col-lg-10">
		        <h2 class="display-3 text-white">Get in touch</h2>
		        <p class="lead text-white">According to the National Oceanic and Atmospheric Administration, Ted, Scambos, NSIDClead scentist, puts the potentially record low maximum sea ice extent tihs year down to low ice.</p>
		      </div>
		    </div>
		    
		  </div>
		  
		</section>
		<section class="section section-lg pt-lg-0 section-contact-us">
		  <div class="container">
		    <div class="row justify-content-center mt--300">
		      <div class="col-lg-8">
		        <div class="card bg-gradient-secondary shadow">
		          <div class="card-body p-lg-5">
		            <h4 class="mb-1">Did you need help?</h4>
		            <p class="mt-0">Feel free to contact us.</p>
		            <div class="form-group mt-5">
		              <div class="input-group input-group-alternative">
		                <div class="input-group-prepend">
		                  <span class="input-group-text"><i class="ni ni-user-run"></i></span>
		                </div>
		                <input class="form-control" placeholder="Your name" type="text">
		              </div>
		            </div>
		            <div class="form-group">
		              <div class="input-group input-group-alternative">
		                <div class="input-group-prepend">
		                  <span class="input-group-text"><i class="ni ni-email-83"></i></span>
		                </div>
		                <input class="form-control" placeholder="Email address" type="email">
		              </div>
		            </div>
		            <div class="form-group mb-4">
		              <textarea class="form-control form-control-alternative" name="name" rows="4" cols="80" placeholder="Type a message..."></textarea>
		            </div>
		            <div>
		              <button type="button" class="btn btn-default btn-round btn-block btn-lg">Send Message</button>
		            </div>
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>
		</section>
		<section class="section section-lg">
		  <div class="container">
		    <div class="row row-grid justify-content-center">
		      <div class="col-lg-12 text-center">
		        
		        <div class="text-center">
		          <h4 class="display-4 mb-5 mt-5">Payments Methods</h4>
		          <div class="row justify-content-center">

		            <div class="col-lg-2 col-4">
		              <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Visa">
		                <img alt="image" src="{{ asset('frontend/img/payments/1.png') }}" class="img-fluid">
		              </a>
		            </div>
		            <div class="col-lg-2 col-4">
		              <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Master Card">
		                <img alt="image" src="{{ asset('frontend/img/payments/2.png') }}" class="img-fluid">
		              </a>
		            </div>
		            <div class="col-lg-2 col-4">
		              <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Paypal">
		                <img alt="image" src="{{ asset('frontend/img/payments/3.png') }}" class="img-fluid opacity-3">
		              </a>
		            </div>
		            <div class="col-lg-2 col-4">
		              <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Western">
		                <img alt="image" src="{{ asset('frontend/img/payments/4.png') }}" class="img-fluid">
		              </a>
		            </div>

		            <div class="col-lg-2 col-4">
		              <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Amirecan">
		                <img alt="image" src="{{ asset('frontend/img/payments/5.png') }}" class="img-fluid">
		              </a>
		            </div>

		            <div class="col-lg-2 col-4">
		              <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Discover">
		                <img alt="image" src="{{ asset('frontend/img/payments/6.png') }}" class="img-fluid">
		              </a>
		            </div>
		            
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>
		</section>
	</main>

@endsection