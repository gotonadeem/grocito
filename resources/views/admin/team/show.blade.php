@extends('admin.layout.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <strong>Plans Details</strong>
          </h1>
          {{--<ol class="breadcrumb">
            <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li><a href="{{ URL::to('admin/plans') }}">Ads</a></li>
            <li class="active">View</li>
          </ol>--}}
        </section
        
        ><section class="content">
           <div class="box box-info">
                <div class="box-header">
                  <a href="{{ URL::to('admin/plans') }}" class="pull-right btn btn-info btn-sm" ><i class="fa fa-info"></i> View All</a>
                </div><!-- /.box-header -->
            </div>

          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
            </div>
            <div class="box-body">
                 <table  class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th width="15%" >Name</th>
                         <td><?php echo $plans->name;?></td>
                          </tr>
                      <tr>
                          <th width="15%">Listing</th>
                          <td><?php echo $plans->listing;?></td>
                      </tr>
                      <tr>
                          <th width="15%">Days</th>
                          <td><?php echo $plans->days;?></td>
                      </tr>
                          <tr>
                        <th width="15%">city</th>
                         <td>{{$plans->city->name or '' }}</td>
                          </tr>
                      <tr>
                          <th width="15%">Description</th>
                          <td><?php echo $plans->description;?></td>
                      </tr>
                      <tr>
                          <th width="15%">Price</th>
                          <td><?php echo $plans->price;?></td>
                      </tr>
                      <tr>
                          <th width="15%">Discount</th>
                          <td><?php echo $plans->discount;?></td>
                      </tr>
                      <tr>
                          <th width="15%">Plan Start Date</th>
                          <td><?php echo date('m-d-Y', strtotime($plans->startdate));?></td>
                      </tr>

                      <tr>
                          <th width="15%">Plan End Date</th>
                          <td><?php echo date('m-d-Y', strtotime($plans->enddate));?></td>
                      </tr>
                           <tr>
                        <th width="15%">Status</th>
                        <th><?php if($plans->status=='1'){ echo 'Active';}else{echo 'Inactive';}?></th>
                      </tr>
                       
                    </thead>
                    <tbody>
                                                      
                    </tbody>
                    
                  </table>
             
            </div><!-- /.box-body -->
           
          </div><!-- /.box -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@stop