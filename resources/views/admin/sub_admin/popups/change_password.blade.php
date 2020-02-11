	<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Password</h4>
      </div>
      <div class="modal-body">
	   <div id="msg"></div>
       <form id="password_form" name="password_form">
	   <input type="hidden" name="user_id" id="user_id">
	       <div class="form-group">
		      <label for='email'>New Password</label>
			  <input type="text" name="password" class="form-control" id="password">
		   </div>
		   <div class="form-group">
		      <label for='subject'>Confirm Password</label>
			  <input type="text" name="password_confirmation" class="form-control" id="password_confirmation">
		   </div>
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="change_password_now()" class="btn btn-primary">Change Password</button>
      </div>
    </div>

  </div>
</div>
