 <div class="row border-bottom">

        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
               
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li>
                <div class="dropdown notifaction">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" onclick="update_notify_view_status()">
                            <i class="fa fa-bell" ></i>
                            <?php $count = Helper::getAdminNotifyCount();
                                if($count > 0){ ?>
                                    <span class="notify_count">{{$count}}</span>
                             <?php   }
                            ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-form">
                            <?php $notify = Helper::getAdminNotification();
                                if(count($notify) > 0)

                            ?>
                                @foreach($notify as $vs)

                                    <?php
                                    $title ='';
                                    if($vs->type =='order_placed'){
                                        $orderData = Helper::getOrderById($vs->int_val);
                                        if($orderData){
                                            $title = $orderData->order_id;
                                        }else{
                                            $title ='';
                                        }

                                    }
                                    if($vs->type =='seller_join'){
                                        $sellerData = Helper::getSellerById($vs->int_val);
                                        if($sellerData){
                                            $title = $sellerData->username;
                                        }else{
                                            $title ='';
                                        }

                                    }
                                    if($vs->type =='product_upload'){
                                        $prodctData = Helper::getProductById($vs->int_val);
                                        if($prodctData){
                                            $title = $prodctData->name;
                                        }else{
                                            $title ='';
                                        }

                                    }
                                    if($vs->type =='user_call_request'){
                                        $userData = Helper::getUserById($vs->int_val);
                                        if($userData){
                                            $title = $userData->username;
                                        }else{
                                            $title ='';
                                        }

                                    }
                                    if($vs->type =='order_return'){
                                        $orderData = Helper::getOrderById($vs->int_val);
                                        if($orderData){
                                            $title = $orderData->order_id;
                                        }else{
                                            $title ='';
                                        }

                                    }
                                    if($vs->type =='order_exchange'){
                                        $orderData = Helper::getOrderById($vs->int_val);
                                        if($orderData){
                                            $title = $orderData->order_id;
                                        }else{
                                            $title ='';
                                        }

                                    }
                                    if($vs->type =='order_cancel'){
                                        $orderData = Helper::getOrderById($vs->int_val);
                                        if($orderData){
                                            $title = $orderData->order_id;
                                        }else{
                                            $title ='';
                                        }

                                    }

                                        ?>
                              <p class="dropdown-item">{{$vs->message}} <br>{{$title}}
                                  <span><b>Date:</b>{{date('d-m-Y h:m:i',strtotime($vs->created_at))}}</span>
                            </p>
                           @endforeach
                        </div>
                    </div>  
                </li>

                <li>
                    <a href="{{ URL::to('admin/logout') }}">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
               
            </ul>

        </nav>
    </div>

 <script>
     BASE_URL='{{ URL::to('/') }}';
     function update_notify_view_status() {

         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: BASE_URL+'/admin/update-notify-view-status',
             type: 'POST',
             //data: {},
             success: function (data) {
                 response= JSON.parse(data);
                 if(response.status)
                 {
                     $('.notify_count').hide();
                 }
                 else
                 {
                     //alert("Please Try Again");
                 }
             },
             error: function () {
                 console.log('There is some error. Please try again.');
             }
         });


     }
     $("document").ready(function() {

         $('.dropdown-menu').on('click', function(e) {
             if($(this).hasClass('dropdown-menu-form')) {
                 e.stopPropagation();
             }
         });
     });
 </script>