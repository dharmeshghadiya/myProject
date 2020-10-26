@extends('admin.layouts.master2')
@section('css')

<link rel="stylesheet" href="{{URL::asset('assets/font/become-a-delaer/material-design-iconic-font/css/material-design-iconic-font.css')}}">
    <link href="{{URL::asset('assets/css/become-a-dealer/style.css')}}" rel="stylesheet">
    <!-- Internal Select2 css -->

@endsection
@section('content')
<div class="container-fluid">
<div class="row no-gutter">
    <div class="wrapper">

            <form method="POST" id="wizard" data-parsley-validate="">
               @csrf
            <!-- SECTION 1 -->
                <h2></h2>
                <section>
          <div class="inner">
            <div class="image-holder" id="loginBg">
              <img src="{{URL::asset('assets/img/media/loginBg.png')}}" alt="" width="80%">
            </div>
            <div class="form-content" >
              <div class="form-header">
                <h3>Become A Dealer </h3>
              </div>
              <p>Please fill with your Business Details</p>
              <div class="form-row">
                <div class="form-holder">
                  <label>Business Name*</label>
                  <input type="text" placeholder="Business Name" id="business_name" name="business_name" class="form-control" >
                  <div class="help-block with-errors error"></div>
                </div>
                <div class="form-holder">
                  <label>Business Number*</label>
                  <input type="number" placeholder="Business Number" id="business_number" name="business_number" class="form-control">
                </div>
              </div>
              <div class="form-row">
                <div class="form-holder w-100">
                   <label>Address*</label>
                  <textarea name="address" id="address" placeholder="Address" class="form-control" style="height: 99px;"></textarea>
                </div>
              </div>
              <div class="form-row">
                <div class="form-holder w-100">
                  <label>Dealer Logo*</label>
                  <input type="file"  id="logo" name="logo" class="form-control ">
                </div>
                
              </div>
              
              
            </div>
          </div>
                </section>

        <!-- SECTION 2 -->
                <h2></h2>
                <section>
                    <div class="inner">
            <div class="image-holder" id="loginBg">
              <img src="{{URL::asset('assets/img/media/loginBg.png')}}" alt="" width="80%">
            </div>
            <div class="form-content">
              <div class="form-header">
                <h3>Become A Dealer</h3>
              </div>
              <p>Please fill with Contact Details</p>
              <div class="form-row">
                <div class="form-holder">
                  <label>Name*</label>
                  <input type="text" placeholder="Enter Name" id="name" name="name" class="form-control">
                </div>
                <div class="form-holder">
                  <label>License Number*</label>
                  <input type="text" placeholder="Enter License Number" class="form-control" id="license_number" name="license_number">
                </div>
                
              </div>
              <div class="form-row">
                <div class="form-holder">
                  <label>Country Code*</label>
                  <select id="country_code" name="country_code" class="form-control" required>
                  <option value=""> Please Select Country Code </option>
                     @foreach($countries as $country)
                     <option value="{{ $country->code }}"> {{ $country->name.'  +'.$country->code }} </option>
                   @endforeach
                 </select>
                </div>
                <div class="form-holder">
                  <label>Mobile Number*</label>
                  <input type="number" placeholder="Enter Mobile Number" id="mobile_number" name="mobile_number" class="form-control integer">
                </div>
              </div>
              <div class="form-row">
                <div class="form-holder">
                  <label>Email*</label>
                  <input type="email" placeholder="Enter Email" class="form-control" name="email" id="email">
                </div>
                <div class="form-holder">
                  <label>Password*</label>
                  <input type="password" placeholder="Enter Password" class="form-control" id="password" name="password">
                </div>
              </div>
              <div class="form-row">
                <div class="form-holder">
                  <label>Security Deposit*</label>
                  <input type="number" placeholder="Enter Security Deposit" class="form-control" name="security_deposite" id="security_deposite">
                </div>
                <div class="form-holder">
                  <label>License Expiry Date*</label>
                  <input type="text" placeholder="Enter License Expiry Date" class="form-control fc-datepicker" name="license_expiry_date" id="license_expiry_date">
                </div>
                
              </div>
              
              <div class="form-row"></div>


            </div>
          </div>
                </section>

                <!-- SECTION 3 -->
                <h2></h2>
                <section>
                    <div class="inner">
            <div class="image-holder" id="loginBg">
              <img src="{{URL::asset('assets/img/media/loginBg.png')}}" alt="" width="80%">
            </div>
            <div class="form-content">
              <div class="form-header">
                <h3>Become A Dealer</h3>
              </div>
              <p>Please fill with Bank Details</p>
              <div class="form-row">
                
                 <div class="form-holder">
                  <label>Bank Name*</label>
                  <input type="text" placeholder="Enter Bank Name" class="form-control" name="bank_name" id="bank_name">
                </div>
                <div class="form-holder">
                  <label>Bank Address*</label>
                  <input type="text" placeholder="Enter Bank Address" class="form-control" id="bank_address" name="bank_address">
                </div>
                
              </div>
              <div class="form-row">
                
                 <div class="form-holder">
                  <label>Bank Contact Number*</label>
                  <input type="number" placeholder="Enter Bank Contact Number" class="form-control" name="bank_contact_number" id="bank_contact_number">
                </div>
                <div class="form-holder">
                  <label>Beneficiary Name*</label>
                  <input type="text" placeholder="Enter Beneficiary Name" class="form-control" id="beneficiary_name" name="beneficiary_name">
                </div>
                
              </div>
              <div class="form-row">
                
                 <div class="form-holder">
                  <label>Bank Code*</label>
                  <input type="text" placeholder="Enter Bank Code" class="form-control" name="bank_code" id="bank_code">
                </div>
                <div class="form-holder">
                  <label>Bank IBAN*</label>
                  <input type="text" placeholder="Enter Bank IBAN" class="form-control" id="bank_iban" name="bank_iban">
                </div>
                
              </div>
              <div class="form-row">
                
                 <div class="form-holder">
                  <label>Trade License Doc*</label>
                  <input type="file"  class="form-control" name="trade_license_doc" id="trade_license_doc">
                </div>
                <div class="form-holder">
                  <div class="checkbox mt-24">
                <label>
                  <input type="checkbox" id="accept_agreemment" name="accept_agreemment" >  Accept Agreemment
                  <span class="checkmark"></span>
                </label>
              </div>
                </div>
                
              </div>

              <div class="form-row">
                
                 <div class="form-holder">
                 
                  <span id="agreemment_required" style="color:red"></span>
                </div>
                
                
              </div>
              
            </div>
          </div>
            </section>
            
    </form>
    </div>
 </div>
 </div>
@endsection
@section('js')
<script>
    var path = {!! json_encode(url('/')) !!}
    var is_admin_open = 0;

</script>
<script src="{{URL::asset('assets/js/custom/becomeADealer.js')}}?v={{ time() }}"></script>
<script src="{{URL::asset('assets/js/jquery.steps.js')}}"></script>
<script src="{{URL::asset('assets/js/main.js')}}"></script>

@endsection
