<div class="modal fade custom_popup" id="cencel_model" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Are you sure you want to cancel?</h4>
				<input type="hidden" name="order_value" id="order_value">
            </div>
            <div class="modal-body">
               <!-- <div class="cntt"><strong>Warning:</strong>Cancellation might lead to a penalty. We will contact you before confirming this cancellation.</div>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-default cancel" onclick="order_cancel()" >Yes</button>
            </div>
        </div>
    </div>
</div>