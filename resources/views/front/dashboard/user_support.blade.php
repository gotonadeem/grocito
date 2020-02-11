@extends('front.layout.front')
@section('content')

 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

<section class="myOrder my-5">

<div class="container">
  <div class="row mb-3">
    <div class="col-6">
      <h2 class="shoping-cart-text">Supports</h2>
    </div>
    <div class="col-6 text-right">

      {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAccount">Add Complaint</button>--}}
    </div>
  </div>
  <ul class="nav nav-tabs wallet-tabs">
    <!--<li class="active"><a data-toggle="tab" href="#home" class="wallet-tab active">Support Chat</a></li>-->
    <li><a data-toggle="tab" href="#raising" class="wallet-tab active">Raising Complaint</a></li>
    <button type="button" class="btn btn-primary ml-3" data-toggle="modal" data-target="#callRequest" >Call Request</button>
  </ul>
<div class="myorder-inner">

<div class="order-table">
<div class="table-responsive">
  {{--<div id="home" class="tab-pane fade in show active">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="transaction-history mt-sm-3 mt-3">
          <h5>Support History</h5>
          <table class="table table-bordered table-striped">
            <thead>
            <tr class="bg-blue">
              <th>S.No</th>
              <th>ID</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Reply</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($complaint as $ks=>$vs)

              <tr>
                <td>{{$ks+1}}</td>
                <td>#{{$vs->complaint_id}}</td>
                <td>{{$vs->subject}}</td>
                <td>{{$vs->complaint_message}}</td>
                <td>{{$vs->reply}}</td>
                <td>{{$vs->created_at}}</td>
                <td>{{$vs->status}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>--}}
  <div id="raising" class="tab-pane fade in show active">
    <div class="transaction-history mt-sm-3 mt-3">
      <h5>Raising Complaint</h5>
      <table class="table table-bordered table-striped">
        <thead>
        <tr class="bg-blue">
          <th width="30px">S.No.</th>
          <th>Complaint Id</th>
          <th>Title</th>
          <th>Problem</th>
          <th>Solution</th>
          <th>Date</th>

        </tr>
        </thead>
        <tbody>
        @foreach($raising as $ks=>$vs)

          <tr>
            <td>{{$ks+1}}</td>
            <td>#{{$vs->complaint_id}}</td>
            <td>{{$vs->title}}</td>
            <td>{{$vs->problem}}</td>
            <td>{{$vs->solution}}</td>
            <td>{{$vs->created_at}}</td>
          </tr>
        @endforeach

        </tbody>
      </table>
    </div>
  </div>
{{--<table class="table table-bordered" id="example">
    <thead>
      <tr>
        <th>S.No</th>
        <th>ID</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Reply</th>
        <th>Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($complaint as $ks=>$vs)

      <tr>
        <td>{{$ks+1}}</td>
        <td>#{{$vs->complaint_id}}</td>
        <td>{{$vs->subject}}</td>
        <td>{{$vs->complaint_message}}</td>
        <td>{{$vs->reply}}</td>
        <td>{{$vs->created_at}}</td>
        <td>{{$vs->status}}</td>
      </tr>
     @endforeach

    </tbody>
  </table>--}}




  <form id="support_form" name="support_form">
    <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Get Support</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="add-account-module">
              <div class="form-group row">
                <div class="col-md-4">
                  <label>Subject</label>
                </div>
                <div class="col-md-8">
                  <input type="text" id="subject" name="subject" class="form-control" value="">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-4">
                  <label>Message</label>
                </div>
                <div class="col-md-8">
                  <textarea class="form-control" name="complaint_message" row="5" id="complaint_message"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="submit" class="btn btn-primary">Send</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form id="call_request" name="call_request">
    <div class="modal fade" id="callRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Call Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="alert alert-success alert" style="display: none">

          </div>
          <div class="modal-footer">
            <button type="button"  data-dismiss="modal" class="btn btn-danger" aria-label="Close">
              Close
            </button>
            <button type="button" onclick="callRequest()" class="btn btn-primary">Send</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>


</div>

</div>




</div>




</div>

</section>
@section('scripts')
  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
      var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
      s1.async=true;
      s1.src='https://embed.tawk.to/5e0aeb2227773e0d832b44ca/default';
      s1.charset='UTF-8';
      s1.setAttribute('crossorigin','*');
      s0.parentNode.insertBefore(s1,s0);
    })();
  </script>
  <!--End of Tawk.to Script-->
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
} );
  </script>
  
  <script>
$(document).ready(function(){
  $(".dataTable").parent().addClass('table-responsive-mob');
});
</script>
  
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ URL::asset('public/js/validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('public/front/developer/js/page_js/support.js') }}"></script>
  <script>
    function callRequest() {
      $(".loader-div").show();
      jQuery.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/send-call-request',
        type: 'POST',
        success: function (data) {
          setTimeout(function() {
            $('.alert').text('Request send successfully').show();
            //$("#callRequest").hide();
            location.reload();
          }, 1000);
          $(".loader-div").hide();
        },

        error: function (error) {

          console.log('erorrr');

        }

      });
    }
  </script>

@stop
@endSection