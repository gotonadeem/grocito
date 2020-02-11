<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Payment Details</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="panel panel-default">

                <div class="panel-body">
                    <form method="POST" id="payment-search-form" class="form-inline" role="form">

                        <div class="col-md-4">
                            <input type="text" name="daterange" readonly class="form-control" id="time_search"  required="" placeholder="Select Date Range to Refine Records."/>
                        </div>

                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
            <table id="payment_list" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Transaction Id</th>
                    <th>Payment Description</th>
                    <th>Total Amount</th>
                    <th>Created at</th>
                </tr>
                </thead>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div><!-- /.col -->
@push('scripts')
<script>
    $(function() {
        var oTable =$('#payment_list').DataTable({
           /* dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
            "<'row'<'col-xs-12't>>"+
            "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",*/
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('payments.datatables.data') !!}',
                data: function (d) {
                    d.daterange = $('input[name=daterange]').val();
                }
            },
            "lengthMenu": [[10], [10]],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'payment_transaction_id', name: 'payment_transaction_id' },
                { data: 'payment_total_cost', name: 'payment_total_cost' },
                { data: 'payment_description', name: 'payment_description' },
                { data: 'created_at', name: 'created_at' },
            ]
        });
        $('#payment-search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
        $('.dataTables_filter input').attr("placeholder", "Enter keyword");
    });
    $(".first").prepend("<b>From</b>");
    $(".second").prepend("<b>To</b>");

</script>
<style>
    .daterangepicker .calendar {
        display: none;
        max-width: 270px;
        margin: 39px !important;
    }
    #payment_list_filter 
    {
        display: none;
    }
</style>
@endpush