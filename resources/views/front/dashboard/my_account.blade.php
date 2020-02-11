@extends('front.layout.front')
@section('content')
<section class="myAccount my-5">
  <div class="container">

    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="account-info row">
          <div class="col-sm-6">
          <div class="title">
            <h3 class="m-0">Account Information  </h3>
          </div>
          <div class="block-content">
            <div class="row">
              <div class="col-12">
                <div class="box box-information row m-0">
                  <div class="col-sm-4">
				    <?PHP
					if($user_info['profile_image']!=""):
						$url=URL::to('public/front/user_profile/'.$user_info['profile_image']);
					 else:
                        $url=URL::to('public/images/default.png'); 	
                    endif;
					
					?>
                    <img src="{{$url}}" style="width: 100px; height: 100px; border-radius: 100%; margin-top: 10px;">
                  </div>
                  <div class="col-sm-8">
                    <strong class="box-title"> <span>Contact Information</span> </strong>
                    <div class="box-content">
                      <p>Name: {{$user_info['f_name']." ".$user_info['l_name']}}<br>
                        Email: {{Auth::user()->email}}<br>
                        Mobile: {{Auth::user()->mobile}}<br>
                      </p>
                    </div>
                    <div class="box-actions">
                      <a class="action edit" href="{{URL::to('update-profile/'.$user_info['user_id'])}}">
                        <span>Edit Profile</span>
                      </a> {{--<a href="{{URL::to('change-password')}}" class="action change-password"> Change Password </a>--}} </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          </div>
          <div class="col-sm-6">
          <div class="title">
            <h3 class="m-0">Address Book <a href="#" class="maddress float-right"><span>Manage Addresses</span></a></h3>
          </div>
          <select class="form-control col-6" onchange="get_address(this.value)">
            <option>Select Address</option>
            @foreach($address as $vs)

            <option value="{{$vs->id}}">{{$vs->address}}</option>


          @endforeach
          </select>
          <div class="block-content">
          <div class="set-address-box mt-4">


            </div>
          </div>
          </div>
          
          
        </div>
      </div>
    </div>
  </div>
</section>
@endSection
<script>
  function get_address(value) {
    $(".loader-div").show();
    jQuery.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: BASE_URL + '/get-account-address',
      type: 'POST',
      data:{address_id:value},
      success: function (data) {
        $(".set-address-box").html(data);
        $(".loader-div").hide();
      },

      error: function (error) {

        console.log('erorrr');

      }

    });
  }
  function delete_address(id) {
    bootbox.confirm({
      message:'Are you sure you want to delete this address.',
      buttons: {
        confirm: {
          label: 'Delete',
          className: 'btn-success'
        },
        cancel: {
          label: 'Cancle',
          className: 'btn-danger'
        }
      },
      callback: function (result) {
        if(result==true)
        {
          $(".loader-div").show();
          jQuery.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL + '/checkout/delete-address',
            type: 'POST',
            dataType:'json',
            data:{id:id},
            success: function (data) {
              if(data.status)
              {
                $(".loader-div").hide();
                location.reload();
              }
            }
          });
        }
      }
    });

  }
  function referralCodeCopy() {
    /* Get the text field */
    var copyText = document.getElementById("referral_code");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    alert("Refrral code copied: " + copyText.value);
  }
</script>