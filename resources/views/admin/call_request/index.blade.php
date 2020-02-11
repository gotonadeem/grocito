@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Call Requests</h5>
                        <div class="ibox-tools">
                          <ul class="tab-top list-inline">
                             
                             </ul>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="call-request" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Created_at</th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Account Details</h4>
      </div>
      <div class="modal-body" id="content">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

@include('admin.includes.admin_right_sidebar')
@include('admin.payment.deposite')
@include('admin.payment.withdraw')
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
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/call_request.js') }}"></script>
<script>
		$('.input-sm').attr('placeholder',"username");
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
    <script>
        function change_status(id){
            var value= id.split(",");

            var result = confirm("Are you sure you want to change the status ?");

            if (result) {

                $('#ajaxLoader').show();

                $.ajax({

                    headers: {

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    },

                    url: BASE_URL+'/admin/update-support-status',

                    type: 'POST',

                    data: {id: value[1],status:value[0] },

                    success: function (data) {

                        location.reload();

                    },

                    error: function () {

                        console.log('There is some error in user deleting. Please try again.');

                    }

                });

                return false;

            }

        }
    </script>
@stop
