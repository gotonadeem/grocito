<div class="modal fade custom_popup" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
		<input type="hidden" class="order_id" id="order_id">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Expected Dispatch Date</h4>
            </div>
            <div class="modal-body">
                <p>Select Expected Dispatch Date</p>
                <p>Date:
                    <input type="text" class="expected_date" id="datepicker">
                    <span class="order_date_msg"></span>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="expected_date" class="btn btn-default submit">Submit</button>
            </div>
        </div>
    </div>
</div>