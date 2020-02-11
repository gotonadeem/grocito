@extends('front.layout.front')
@section('content')
  <style>
    .txt-red-color{
      color: red;
    }
    .txt-green-color{
      color: green;
    }
  </style>
<?PHP
$array_list=array('coral'=>'pending','green'=>'approved','red'=>'rejecetd');
?>
<div class="container-fluid my-3">
  <ul class="breadcrumb justify-content-start">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Wallet</li>
  </ul>
</div>
<div class="alert alert-success message" style="display:none" id="success_alert"><li class="fa fa-check"></li></div>
@if(Session::has('success_message'))
  <div class="alert alert-success message"><li class="fa fa-check"></li>{{ Session::get('success_message') }}</div>
@endif
@if(Session::has('error_message'))
  <div class="alert alert-error message"><li  class="fa fa-window-close"></li>{{ Session::get('error_message') }}</div>
@endif
  <div class="container-fluid">
    <!--Start Dashboard Content-->
    <div class="invite-earn">
    <div class="refer-code">
      <div class="input-group w-50">
        <input type="text" class="form-control" id="myReferCode" placeholder="some text" value="{{$user->reff_code}}" readonly="">
        <div class="input-group-append">
          <button type="submit" class="btn btn-default input-group-text" onclick="referralCodeCopy()">Copy Url</button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="reffer-box">
          <h3>Share Your Referral Link</h3>
          <div class="share-box mt-4">
                <h5>Social Media</h5>
                <p>Share on :</p>
                <a href="javascript:void(0)" class="btn btn-facebook waves-effect waves-light" id="share_refer_code">
                  <i class="fa fa-facebook-square"></i> Share with facebook
                </a>
                <a href="https://api.whatsapp.com/send?text={{$user->reff_code}}" class="btn btn-success waves-effect waves-light">
                  <i class="fa fa-whatsapp"></i> Share with whatsapp
                </a>
                <a href="javaScript:void(0)" data-toggle="modal" data-target="#myEmailModel" class="btn btn-email waves-effect waves-light m-1">
                  <i class="fa fa-google-plus"></i> Share with Email
                </a>
              </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="reffer-dis text-center" id="refeearn">
        <h3>REFER A FRIEND</h3>
            {!!$content->referral_description!!}
    
        </div>
      </div>
    </div>
    </div>
    
    <!--End Row-->
    <!--End Dashboard Content-->
  </div>
  <div id="myEmailModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="rsuccessMsg"></div>
        <div class="modal-header-custom">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="text-center title-signup">Send Email</h4>
        </div>
        <div class="success_message" style="display: none">Send Successfully</div>
        <div class="modal-body">
          <div class="col-sm-12">
            <label>Refer Code</label>
            <input type="text" class="form-control" name="refer_code" id="refer_code" placeholder="refer code" value="{{$user->reff_code}}"b readonly>
            <span class="error" id="refer_code"></span>
          </div>
        </div>
        <div class="modal-body">
          <div class="col-sm-12">
            <label>Email Address</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email address" required>
            <span class="error" id="email_msg"></span>
          </div>
        </div>

        <div class="modal-body">
          <div class="col-sm-12">
            <label>Message</label>
            <input type="text" class="form-control" name="message" id="message" placeholder="message">
            <span class="error" id="refer_code"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" onclick="send_email_code()" class="btn btn-primary custom-btn">Send</button>
        </div>
      </div>
    </div>
  </div>
@section('scripts')
  <script src="{{ asset('public/js/share.js') }}"></script>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      FB.init({appId: '2928389843902996', status: true, cookie: true,
        xfbml: true});
    };
    (function() {
      var e = document.createElement('script'); e.async = true;
      e.src = document.location.protocol +
              '//connect.facebook.net/en_US/all.js';
      document.getElementById('fb-root').appendChild(e);
    }());
  </script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
} );

  $(document).ready(function(){
    $('#share_refer_code').click(function(e){
      e.preventDefault();
      FB.ui(
              {
                method: 'feed',
                name: 'sdfsd fsd sfsd fdsf sdfdsf',
                link: '{{URL::to("/")}}',
               // picture: "",
                caption: $("#myReferCode").val(),
                description: "sd fs dfdsf sfsdf",
                message: "Refer codes df sdfs dfs df"
              });
    });
  });
  function send_email_code() {
    var email =$('#email').val();
    var refer_code = $('#refer_code').val();
    var message = $('#message').val();
    $(".success_message").hide();
    if(email ==''){
      $('#email_msg').text('Email Required');
      return false;
    }
    $(".loader-div").show();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: BASE_URL + '/user/send-email-refer',
      data: {email:email,refer_code:refer_code,message:message},
      cache: false,
      success: function (response, textStatus, jqXHR) {
        $(".success_message").show();
        $("#myEmailModel").modal('hide');
        $(".loader-div").hide();
      }
    });
  }
  function referralCodeCopy() {
    /* Get the text field */
    var copyText = document.getElementById("myReferCode");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    alert("Refrral code copied: " + copyText.value);
  }
  </script>
  
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

@stop
@endSection