<div id="depositModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Deposit Amount To Seller</h4>
      </div>
      <div class="modal-body">
	                 <div class="alert  alert-success"  id="success_alert_deposite">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <span id="error_deposit"></span>
                    </div>

                    <div class="alert alert-danger"  id="danger_alert_deposite">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <span id="error_msg_deposit"></span>
                    </div>
       <form id="deposite_form" name="deposite_form">
	       <div class="form-group">
		      <label for='Amount'>Amount</label>
			  <input type="text" name="amount" class="form-control" id="amount">
		   </div> 
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="deposite_amount" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
