<!-- Modal -->
<div id="planModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
	  <div id="msg_error"></div>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<input type="hidden" value="" id="product_id_value">
        <h4 class="modal-title">Select Plan To Display Your Product In Special Section</h4>
		<input type="hidden" id="seller_id" name="user_id" value="{{$product->user_name->id}}">
      </div>
      <div class="modal-body">
				  <div class="col-md-4">
					  <div class="purchase-tag">
					   <?PHP 
					   $date= array();
					   foreach($weekOfdays as $vs1): 
						  $data= explode(":",$vs1);

						  if(trim($data[0])=="Monday" or trim($data[0])=="Tuesday" or trim($data[0])=="Wednesday" or trim($data[0])=="Thursday")				  
						  {
							  echo $vs1."<br>";
							  array_push($date,trim($data[1]));
						  }		  
					   endforeach; ?>
						<input type="hidden" id="date_1" value="<?=implode($date,",");?>">
					   <span class="price-amount">Free</span>
					   <div class="radio-box-custom">
						<input type="radio" name="plan" checked value="1">
						<span class="checkmark"></span>	
						</div>
					  </div>
					</div>  
				  <div class="col-md-4">
					   <div class="purchase-tag">
					   <?PHP 
					   $date1= array();
					   foreach($weekOfdays as $vs): 
						  $data1= explode(":",$vs);
						  
						  if(trim($data1[0])=="Friday" or trim($data1[0])=="Saturday" or trim($data1[0])=="Sunday")				  
						  {
							  echo $vs."<br>";
							  $date1[]=trim($data1[1]);
						  }				  
					   endforeach;?>
						<input type="hidden" id="date_2" value="<?=implode($date1,",");?>">
					   <span class="price-amount"> Free </span>
					   <div class="radio-box-custom">
					   <input type="radio" name="plan" value="2">
					   <span class="checkmark"></span>	
					   </div>
					  </div>
					  </div>  
				     <div class="col-md-4">
					  <div class="purchase-tag">
					   <?PHP 
					   $date1= array();
					   foreach($weekOfdays as $vs): 
						  $data1= explode(":",$vs);
							  echo $vs."<br>";
							  $date1[]=trim($data1[1]);				  
					   endforeach;?>
						<input type="hidden" id="date_3" value="<?=implode($date1,",");?>">
					   <span class="price-amount"> Free </span>
					   <div class="radio-box-custom">
					   <input type="radio" name="plan" value="3">
					   <span class="checkmark"></span>	
					   </div>
					   </div>
					   
					  </div>		  
      </div>
      <div class="modal-footer">   
	   <button type="button" onclick="activate_plan()" class="btn btn-primary" >Request</button>
	   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
