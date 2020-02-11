<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Logged In User</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <table id="user_list" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>S.No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                   
                    <th>Created</th>
                </tr>
                </thead>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div><!-- /.col -->
@push('scripts')
<script>
    $(function() {
        $('#user_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('users.login.datatables.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user.fname', name: 'fname' },
                { data: 'user.lname', name: 'lname' },
                { data: 'user.mobile', name: 'mobile' },
                { data: 'user.email', name: 'email' },
                { data: 'user.created_at', name: 'created_at' },
            ]
        });
    });
</script>
@endpush