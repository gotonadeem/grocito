@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title display">
					   <div class="row">
						 <div class="col-md-6"><h4>View Customer Information</h4></div>
					   </div>
						<div class="row">
						<div class="text-right"><a href="{{ URL::previous() }}" class="btn btn-primary waves-effect waves-light button_margin_right">Back</a> 
                        </div>
						</div>
                        
                           <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">First Name</label>
                                     <div class="col-sm-8">
                                        {{$user->user_kyc->f_name}}
                                     </div>
                                 </div>
                             </div>
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Last Name</label>
                                     <div class="col-sm-8">
                                        {{$user->user_kyc->l_name}}
                                     </div>
                                 </div>
                             </div> 
							 
                             <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Email Address</label>
                                     <div class="col-sm-8">
                                        {{$user->email}}
                                     </div>
                                 </div>
                             </div>
                              
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Mobile</label>
                                     <div class="col-sm-8">
                                        {{$user->mobile}}
                                     </div>
                                 </div>
                             </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Wallet Amount</label>
                                <div class="col-sm-8">
                                   <?php echo Helper::get_wallet($user->id);?>
                                </div>
                            </div>
                        </div>

		        </div>
                </div>
                </div>
            </div>
			
	    <div class="row">
		@foreach($user_address as $ks=>$vs)
		
		   <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Address{{$ks+1}} <?PHP if($vs->is_default==1){ echo "( Active Now )"; } ?> </h5>
                    </div>
                    <div class="ibox-content" style="<?PHP if($vs->is_default==1){ echo "background: green;color: white;"; } ?>" >
                        <ul class="unstyled">
                            <li>Name: {{$vs->name}}</li>

                            <li>House: {{$vs->house}}</li>
                            <li>Street: {{$vs->street}}</li>
                            <li>City: {{$vs->city}}</li>
                            <li>State: {{$vs->state}}</li>

                            <li>Pincode: {{$vs->pincode}}</li>
                            <li>Created At: {{$vs->created_at}}</li>
                        </ul>
                    </div>
                </div>
            </div>
		@endforeach	
			
        </div>	
                    <ul class="nav nav-tabs tabs-bordered">
                            <li class="active">
                                <a href="#home-b1" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-home"></i></span>
                                    <h4>Customer's Order</h4>
			
                                </a>
                            </li>
                            <li>
                                <a href="#settings-b1" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-home"></i></span>
                                    <span class="hidden-xs">Contacts</span>
                                </a>
                            </li>
                           
                        </ul>
				 
				 <div class="tab-content">
            <div class="tab-pane active" id="home-b1">		
			<div class="row">
			<div class="ibox-content">
                            <div class="table-responsive">
                                <table id="order-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
										<th>Order Id</th>
										<th>C-Name</th>
										<th>C-Mobile</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Qty</th>
										<th>P-mode</th>
										<th>Date</th>
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
		    <div class="tab-pane" id="settings-b1">
			               <div class="table-responsive">
                                <table id="contacts-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
										<th>Name</th>
										<th>Mobile No</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?PHP
									/*if(count(json_decode($user->contact_details))>0)
									{
										$i=0;
										$contacts=json_decode($user->contact_details);
									
										foreach($contacts->contact as $vs):
										$i++;
										if($vs->PhoneNumber!='')
										{
										*/?><!--
										<tr>
											<td><?/*=$i*/?></td>
											<td><?/*=$vs->DisplayName*/?></td>
											<td><?/*=$vs->PhoneNumber*/?></td>
										</tr>
										 --><?PHP /*
										}
										 endforeach; 
									}*/
									 ?>
                                    </tbody>
                                </table>
                            </div>
			</div>
		   
		   </div>
		</div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <!-- Page-Level Scripts -->
	<script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
		var customer_id="{{$id}}";
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
   <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/customer.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"username,email");
	</script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
	<script> 
	$('#contacts-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel','pdf'
        ]
    } );
	</script
@stop
