<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="MAVIA - Register, Reservation, Questionare, Reviews form wizard">
  <meta name="author" content="Ansonika">
  <title>RydeZilla</title>

  <!-- Favicons-->
  <link rel="icon" href="{{URL::asset('assets/img/brand/favicon.png')}}" type="image/x-icon"/>
  <link rel="apple-touch-icon" type="image/x-icon" href="{{URL::asset('assets/img/brand/favicon.png')}}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{URL::asset('assets/img/brand/favicon.png')}}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{URL::asset('assets/img/brand/favicon.png')}}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{URL::asset('assets/img/brand/favicon.png')}}">

  <!-- GOOGLE WEB FONT -->
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/style.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/responsive.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/menu.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/animate.min.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/icon_fonts/css/all_icons_min.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/skins/square/grey.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/date_time_picker.css')}}" rel="stylesheet">
  <link href="{{URL::asset('assets/become-a-dealer/css/custom.css')}}" rel="stylesheet">
  <script src="{{URL::asset('assets/become-a-dealer/js/modernizr.js')}}"></script>
  <!-- Modernizr -->

</head>

<body>

  <div id="preloader">
    <div data-loader="circle-side"></div>
  </div><!-- /Preload -->

  <div id="loader_form">
    <div data-loader="circle-side-2"></div>
  </div><!-- /loader_form -->

  <header>
    <div class="container-fluid">
        <div class="row">
                <div class="col-3">
                    <div id="logo_home">
                        <h1><a href="index.html"></a></h1>
                    </div>
                </div>
                <div class="col-9">
                    <div id="social">
                        <ul>
                            <li><a href="#"><i class="icon-facebook"></i></a></li>
                            <li><a href="#"><i class="icon-twitter"></i></a></li>
                            <li><a href="#"><i class="icon-google"></i></a></li>
                            <li><a href="#"><i class="icon-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
    </div>
    <!-- container -->
  </header>
  <!-- End Header -->

  <main>
    <div id="form_container">
      <div class="row">
        <div class="col-lg-5">
          <div id="left_form">
            <figure><img src="{{URL::asset('assets/img/media/loginBg.png')}}" alt="" width="80%"></figure>
            <h2>Registration</h2>
            <p>Tation argumentum et usu, dicit viderer evertitur te has. Eu dictas concludaturque usu, facete detracto patrioque an per, lucilius pertinacia eu vel.</p>
            <a href="#" id="more_info" data-toggle="modal" data-target="#more-info"><i class="pe-7s-info"></i></a>
          </div>
        </div>
        <div class="col-lg-7">

          <div id="wizard_container">
            <div id="top-wizard">
              <div id="progressbar"></div>
            </div>
            <!-- /top-wizard -->
            <form method="POST"   id="becomeADealerForm" >
              @csrf
              <input id="website" name="website" type="text" value="">
              <input type="hidden" id="form-method" value="add">
              <!-- Leave for security protection, read docs for details -->
              <div id="middle-wizard">

                <div class="step">
                  <h3 class="main_question"><strong>1/3</strong>Please fill with business details</h3>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="business_name" id="business_name" class="form-control required" placeholder="Business Name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="number" name="business_number" id="business_number" class="form-control required " placeholder="Business Number">
                      </div>
                    </div>
                  </div>
                  <!-- /row -->

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input type="text" name="address" id="address" class="form-control required " placeholder="Address"   autocomplete="off">
                        <input type="hidden" class="form-control" name="latitude" id="latitude" placeholder="Latitude" />
                        <input type="hidden" class="form-control" name="longitude" id="longitude" placeholder="Longitude" />
                      </div>
                    </div>
                  </div>
                   <div id="map-canvas" style="height:300px; display: none;"></div>
                  <!-- /row -->

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                       Logo <input type="file"  id="logo" name="logo" class="form-control required ">
                      </div>
                    </div>

                  </div>
                  <!-- /row -->
                </div>
                <!-- /step-->

                <div class="step">
                  <h3 class="main_question"><strong>2/3</strong>Please fill with Contact Details</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" placeholder="Name" id="name" name="name" class="form-control required ">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" placeholder="License Number" class="form-control required " id="license_number" name="license_number">
                      </div>
                    </div>
                    <!-- /col-sm-12 -->
                  </div>
                  <!-- /row -->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="number" placeholder="Security Deposit" class="form-control required " name="security_deposite" id="security_deposite">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" placeholder="License Expiry Date" class="form-control required " name="license_expiry_date" id="check_in">
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                       <div class="styled-select">
                          <select class="required" name="country">
                            <option value="" >Select your country Coode</option>
                            @foreach($countries as $country)
                             <option value="{{ $country->code }}"> {{ $country->name.'  +'.$country->code }} </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="number" placeholder="Mobile Number" id="mobile_number" name="mobile_number" class="form-control required ">
                      </div>
                    </div>
                  </div>
                  <!-- /row -->

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="email" placeholder="Email" id="email" name="email" class="form-control required ">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="password" placeholder="Password" id="password" name="password" class="form-control required ">
                      </div>
                    </div>

                  </div>


                  <!-- /row -->
                </div>
                <!-- /step-->

                <div class="submit step">
                  <h3 class="main_question"><strong>3/3</strong>Please fill with Bank Details</h3>
                  <div class="row">
                    <div class="col-md-12">
                  <label id="msg_success" style="display: none;"></label>
                  <label id="msg_error" style="display: none;"></label>

                </div>
              </div>
                   <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                       <input type="text" placeholder="Bank Name" class="form-control required " name="bank_name" id="bank_name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" placeholder="Bank Address" class="form-control required " id="bank_address" name="bank_address">
                      </div>
                    </div>
                    <!-- /col-sm-12 -->
                  </div>
                  <!-- /row -->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="number" placeholder="Bank Contact Number" class="form-control required " name="bank_contact_number" id="bank_contact_number">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" placeholder="Beneficiary Name" class="form-control required " id="beneficiary_name" name="beneficiary_name">
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" placeholder="Bank Code" class="form-control required " name="bank_code" id="bank_code">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                         <input type="text" placeholder="Bank IBAN" class="form-control required " id="bank_iban" name="bank_iban">
                      </div>
                    </div>
                  </div>
                  <!-- /row -->

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        Trade License Doc<input type="file"  class="form-control required " name="trade_license_doc" id="trade_license_doc">
                      </div>
                    </div>


                  </div>
                  <div class="form-group terms">
                    <input name="terms" type="checkbox" class="icheck required " value="yes">
                    <label>Please accept <a href="#" data-toggle="modal" data-target="#terms-txt"> Agreemment</a> ?</label>


                  </div>
                </div>
                <!-- /step-->
              </div>
              <!-- /middle-wizard -->
              <div id="bottom-wizard">
                <button type="button" name="backward" class="backward">Backward </button>
                <button type="button" name="forward" class="forward">Forward</button>
                <button type="submit" name="process" class="submit">Submit</button>
              </div>
              <!-- /bottom-wizard -->
            </form>
          </div>
          <!-- /Wizard container -->
        </div>
      </div><!-- /Row -->
    </div><!-- /Form_container -->
  </main>

  <footer id="home" class="clearfix">
    <p>© {{date('Y')}} RydeZilla</p>
    <ul>
      <!-- <li><a href="#" class="animated_link">Purchase this template</a></li> -->
      <li><a href="#" class="animated_link">Terms and conditions</a></li>
      <li><a href="#" class="animated_link">Contacts</a></li>
    </ul>
  </footer>
  <!-- end footer-->

  <div class="cd-overlay-nav">
    <span></span>
  </div>
  <!-- cd-overlay-nav -->

  <div class="cd-overlay-content">
    <span></span>
  </div>
  <!-- cd-overlay-content -->

  <!-- <a href="#" class="cd-nav-trigger">Menu<span class="cd-icon"></span></a> -->

  <!-- Modal terms -->
  <div class="modal fade" id="terms-txt" tabindex="-1" role="dialog" aria-labelledby="termsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="termsLabel">Terms and conditions</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <p>Lorem ipsum dolor sit amet, in porro albucius qui, in <strong>nec quod novum accumsan</strong>, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
          <p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus. Lorem ipsum dolor sit amet, <strong>in porro albucius qui</strong>, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
          <p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn_1" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Modal info -->
  <div class="modal fade" id="more-info" tabindex="-1" role="dialog" aria-labelledby="more-infoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="more-infoLabel">Frequently asked questions</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <p>Lorem ipsum dolor sit amet, in porro albucius qui, in <strong>nec quod novum accumsan</strong>, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
          <p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus. Lorem ipsum dolor sit amet, <strong>in porro albucius qui</strong>, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
          <p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn_1" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- SCRIPTS -->
  <!-- Jquery-->
  <script>
    var path = {!! json_encode(url('/')) !!}

</script>
  <script src="{{URL::asset('assets/become-a-dealer/js/jquery-3.2.1.min.js')}}"></script>
  <!-- Common script -->

  <script src="{{URL::asset('assets/become-a-dealer/js/common_scripts_min.js')}}"></script>
  <!-- Wizard script -->

  <!-- Menu script -->
  <script src="{{URL::asset('assets/become-a-dealer/js/velocity.min.js')}}"></script>
  <script src="{{URL::asset('assets/become-a-dealer/js/main.js')}}"></script>
  <!-- Theme script -->
  <script src="{{URL::asset('assets/become-a-dealer/js/functions.js')}}"></script>

  <script src="{{URL::asset('assets/become-a-dealer/js/datepicker_func.js')}}"></script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjbtflnGL0mEj7aHh9VOHPAa_0cqbJabY&libraries=places&callback=initMap" async defer> </script>
  <script src="{{URL::asset('assets/become-a-dealer/js/registration_wizard_func.js')}}"></script>

</body>
</html>
