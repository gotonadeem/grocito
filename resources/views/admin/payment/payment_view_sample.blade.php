@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Payment History</h5>
                        <div class="ibox-tools">
                          <ul class="tab-top list-inline">
                                 <li> <a href="{{URL::to('admin/payment/payment-report')}}" class="btn btn-primary pull-right">BACK</a></li>
                            </ul>
						
                 
                    </div>
                </div>
                <div class="ibox-content">
                                     
				   <div class="table-responsive">
                        <table id="transaction-table" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>W-Start</th>
                                <th>Shipped amount</th>
                                <th>Commission</th>
                                <th>C-Comm</th>
                                <th>GST(18%)</th>
								<th>RMA Charges</th>
                                <th>Seller amount</th>
                               
                                <th style="color:green">P-Amount</th>
                                <th class="text-danger">H-Amount</th>
                                <th>W-end</th>
                                <th>Action</th>
                            </tr>
                            </thead>
							<tbody>
							
							</tbody>	
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.includes.admin_right_sidebar')
@include('admin.payment.deposite')
@include('admin.payment.withdraw')

  <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Payment To Seller</h4>
      </div>
      <div class="modal-body">
	   <div id="msg"></div>
       <form id="mail_form" name="mail_form">
	       <div class="form-group">
		      <label for='email'>Transaction Id</label>
			  <input type="text" name="transaction_id" class="form-control" placeholder="Transaction Id" id="transaction_id">
		   </div> 
		   
		   <div class="form-group">
		      <label for='email'>Bank Name</label>
			  <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" id="bank_name">
		   </div>
		   
		   
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="send_payment" class="btn btn-primary">Send</button>
      </div>
    </div>

  </div>
</div>


<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment Details</h4>
      </div>
      <div class="modal-body">
	   <div id="data_details"></div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Mainly scripts -->
<script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>
<!-- Custom and plugin javascript -->
<script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- Page-Level Scripts -->
<script>
    ASSET_URL = '{{ URL::asset('public') }}/';
    BASE_URL='{{ URL::to('/') }}';
	
</script>
<script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/payment_view.js') }}"></script>
<script>
		//$('.input-sm').attr('placeholder',"username,order id,mobile");
		$('.input-sm').css("font-size","13px");
		
</script>
<script type="text/javascript">
           $(document).ready(function () {
               var daysToAdd = 4;
               $("#FromDate").datepicker({
                   onSelect: function (selected) {
                       var dtMax = new Date(selected);
                       dtMax.setDate(dtMax.getDate() + daysToAdd);
                       var dd = dtMax.getDate();
                       var mm = dtMax.getMonth() + 1;
                       var y = dtMax.getFullYear();
                       var dtFormatted = y + '-'+ mm + '/'+ dd;
                       // $("#ToDate").datepicker("option", "minDate", dtFormatted);
                   }
               });

               $("#ToDate").datepicker({
                   onSelect: function (selected) {
                       var dtMax = new Date(selected);
                       dtMax.setDate(dtMax.getDate() - daysToAdd);
                       var dd = dtMax.getDate();
                       var mm = dtMax.getMonth() + 1;
                       var y = dtMax.getFullYear();
                       var dtFormatted = y + '/'+ mm + '/'+ dd;
                       // $("#FromDate").datepicker("option", "maxDate", dtFormatted)
                   }
               });
           });
</script>
@stop
