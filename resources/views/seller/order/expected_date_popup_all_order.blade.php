<div class="modal fade custom_popup" id="myModal_all_order" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
		<input type="hidden" class="all_order_id" id="all_order_id">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Expected Dispatch Date</h4>
            </div>
            <div class="modal-body">
                <p>Select Expected Dispatch Date</p>
                <p>Date:
                    <input type="text" class="expected_date2" id="datepicker2">
                    <span class="order_all_date_msg"></span>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="expected_date_all" class="btn btn-default submit">Submit</button>
            </div>
        </div>
    </div>
</div>