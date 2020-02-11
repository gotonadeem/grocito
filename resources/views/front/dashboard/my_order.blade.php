@extends('front.layout.front')
@section('content')

 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
 @if (session('success_message'))
   <div class="alert alert-success">
     {{ session('success_message') }}
   </div>
 @endif
 @if (session('error_message'))
   <div class="alert alert-danger">
     {{ session('error_message') }}
   </div>
 @endif
<section class="myOrder my-5">

<div class="container">
<h2 class="shoping-cart-text">My Order</h2>

<div class="myorder-inner">

<div class="order-table">
<div class="table-responsive">
<table class="table table-bordered" id="example">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Order Date</th>
        <th>Order Id</th>
        <th>No of Items</th>
        {{--<th>Status</th>--}}
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $ks=>$vs)

      <tr>
        <td>{{$ks+1}}</td>
        <td>{{$vs->created_at}}</td>
        <td>{{$vs->order_id}}</td>
        <td>{{count($vs->order_meta_data)}}</td>

        {{--<td>{{str_replace('_',' ',$vs->status)}}</td>--}}
        <td><a href="{{URL::to('order-view/'.$vs->id)}}">View</a>
          @if($vs->status =='pending') | <a href="javascript:void(0);" class="text-danger" onclick="openOrderCancleModal('{{$vs->id}}')">Cancel Order</a> @endif
          @if($vs->status =='cancelled') | <span >Order Cancelled</span> @endif
        </td>
      </tr>
     @endforeach

    </tbody>
  </table>
</div>
</div>
</div>
</div>
</div>

</section>
  <!-- Order cancle modal-->
 <div id="orderCancleModel" class="modal fade" role="dialog">
   <div class="modal-dialog">
     <!-- Modal content-->
     <div class="modal-content">
       <div class="modal-header">
         <h4>Order Cancel</h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
       </div>
       <div class="modal-body">
           <div class="form-group">
             <label for='Amount'>Reason</label>
             <textarea  name="reason" class="form-control" id="reason"></textarea>
             <span id="reason-error" class="error" style="display: none"></span>
           </div>

       </div>
       <div class="modal-footer">
         <input type="hidden" id="orderId" value="">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         <button type="button" class="btn btn-primary" onclick="cancleOrder()">Submit</button>
       </div>
     </div>
   </div>
 </div>
@section('scripts')
<script type="text/javascript">
  function openOrderCancleModal(orderId) {
    $('#orderId').val(orderId);
    $('#orderCancleModel').modal('show');
  }
  function cancleOrder() {
    var orderId = $('#orderId').val();
    var reason = $('#reason').val();
    if(reason ==''){
      $('#reason-error').html('This field is required!').show();
      return false;
    }
    $('#reason-error').hide();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: BASE_URL+'/user/user-order-cancle',
      type: 'POST',
      data: {reason: reason, orderId:orderId},
      success: function (data) {
      location.reload();
      },
      error: function () {
        console.log('There is some error in user deleting. Please try again.');
      }
    });
  }
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

@stop
@endSection