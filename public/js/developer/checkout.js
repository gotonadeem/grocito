/**

 * Created by sonukumar.singh on 1/19/2017.

 */

$(function() {



    // only alphanumeric characters allowed

    jQuery.validator.addMethod('alphanumeric', function (value, element) {

        if(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/.test(value))

        {

            return true;

        }

    }, 'Only Alphanumeric characters are allowed.');


//Alphabet only with space
    jQuery.validator.addMethod('onlyAlphabets', function (value, element) {
        if(/^[a-zA-Z\s]+$/i.test(value))
        {
            return true;
        }
    });
    //mobile no validation

    jQuery.validator.addMethod('validMobile', function (value, element) {

        if(/^[0-9]+$/i.test(value))

        {

            return true;

        }

    }, 'Please enter valid mobile no.');

    //Only Alpahabets, space, period or apostrophe allowed

    jQuery.validator.addMethod('validname', function (value, element) {

        if(/^[a-zA-Z.']+$/i.test(value))

        {

            return true;

        }

    }, 'Only characters allowed.');



    //Please enter valid email

    jQuery.validator.addMethod("validEmail", function(value, element)

    {

        if(value == '')

            return true;

        var temp1;

        temp1 = true;

        var ind = value.indexOf('@');

        var str2=value.substr(ind+1);

        var str3=str2.substr(0,str2.indexOf('.'));

        if(str3.lastIndexOf('-')==(str3.length-1)||(str3.indexOf('-')!=str3.lastIndexOf('-')))

            return false;

        var str1=value.substr(0,ind);

        if((str1.lastIndexOf('_')==(str1.length-1))||(str1.lastIndexOf('.')==(str1.length-1))||(str1.lastIndexOf('-')==(str1.length-1)))

            return false;

        str = /(^[a-zA-Z0-9]+[\._-]{0,1})+([a-zA-Z0-9]+[_]{0,1})*@([a-zA-Z0-9]+[-]{0,1})+(\.[a-zA-Z0-9]+)*(\.[a-zA-Z]+)$/;

        temp1 = str.test(value);

        return temp1;

    }, "Please enter valid email.");



    // validate signup form on keyup and submit

    $("#address_form").validate({
        debug:true,
        rules: {
            name: {
                required: true,
                onlyAlphabets: true,
                maxlength:20
            },

            house: {
                required: true,
            },

            street: {
                required: true,
            },
			address: {
                required: true,
            },

            type: {
                required: true,
            }
        },

        messages: {

            name: {
                required: "Name is required"
            },

            house:{
                required: "House is required"
            },
			address:{
                required: "Address is required"
            },
            street: {
                required: "Street is required"
            },


            type: {
                maxlength: "Address type is required"
            },
        },

        submitHandler: function (form) {

        }

    });


    $("#address_edit_form").validate({
        debug:true,
        rules: {
            name: {
                required: true,
                validname: true,
                maxlength:20
            },

            house: {
                required: true,
            },

            street: {
                required: true,
            },
			address: {
                required: true,
            },

            type: {
                required: true,
            }
        },

        messages: {

            name: {
                required: "Name is required"
            },
            address: {
               required: "Address is required"
            },
            house:{
                required: "House is required"
            },
            street: {
                required: "Street is required"
            },


            type: {
                maxlength: "Address type is required"
            },
        },

        submitHandler: function (form) {

        }

    });




});

$(document).ready(function () {
    $('#address_form').validate();
    $('#address_add').click(function () {

        if($("#address_form").valid()) {
            $(".loader-div").show();
            submit_address();
        }
    });
});


$('#address_edit_form').validate();
function address_update()
{
    if($("#address_edit_form").valid()) {
        $(".loader-div").show();
        edit_address_update();
    }
}


function submit_address ()
{

    $(".loader-div").show();
    var city=jQuery("#city").val();
    var state=jQuery("#state").val();

    var name=jQuery("#name").val();
    var street=jQuery("#street").val();
    var title=jQuery("#title").val();
    var address=jQuery("#address").val();
    var house=jQuery("#house").val();
    var mobile=jQuery("#mobile").val();
    var lattitude=localStorage.getItem('lattitude');
    var longitude=localStorage.getItem('longitude');
	
    var type= jQuery("input[name='type']:checked").val();
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/checkout/add-address',
        type: 'POST',
        dataType:'json',
        data:{city:city,state:state,name:name,street:street,house:house,mobile:mobile,type:type,title:title,address:address,lattitude:lattitude,longitude:longitude},
        success: function (data) {
            if(data.status)
            {
                get_address();
                $("#myAddress").modal("hide");
                $(".modal-backdrop").hide();
                $(".loader-div").hide();
                $("#name").val('');
                $("#street").val('');
                $("#house").val('');

            }

            if(data.fail)
            {
                $(".loader-div").hide();
                var json=jQuery.parseJSON(JSON.stringify(data.errors));
                $(".fnameMsg").html(json.fname);
                $(".lnameMsg").html(json.lname);
                $(".emailMsg").html(json.email);
                $(".mobileMsg").html(json.mobile);
                $(".dobMsg").html(json.dob);
                $(".genderMsg").html(json.gender);
                $(".passwordMsg").html(json.password);
                $(".confMsg").html(json.password_confirmation);
                jQuery('.loader').hide();
            }

        },

        error: function (error) {

            console.log(error);

        }

    });
}

function delete_address(id)
{
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
                get_address();
            }
        }
	});
		
}

function edit_address_update()
{

    var id=localStorage.getItem('a_id');
    $(".loader-div").show();
    var form = $("#address_edit_form");
    var name=form.find("#name").val();
    var street=form.find("#street").val();
    var title=form.find("#title").val();
    var house=form.find("#house").val();
    var address=form.find("#address").val();
    var mobile=form.find("#mobile").val();
    var type= form.find("input[name='type']:checked").val();
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/checkout/edit-address',
        type: 'POST',
        dataType:'json',
        data:{name:name,street:street,house:house,mobile:mobile,type:type,title:title,id:id,address:address},
        success: function (data) {
            if(data.status)
            {
                get_address();
                $("#myAddressEdit").hide();
                $(".modal-backdrop").hide();
                $(".loader-div").hide();
                $("#name").val('');
                $("#street").val('');
                $("#house").val('');

            }

            if(data.fail)
            {
                $(".loader-div").hide();
                var json=jQuery.parseJSON(JSON.stringify(data.errors));
                $(".fnameMsg").html(json.fname);
                $(".lnameMsg").html(json.lname);
                $(".emailMsg").html(json.email);
                $(".mobileMsg").html(json.mobile);
                $(".dobMsg").html(json.dob);
                $(".genderMsg").html(json.gender);
                $(".passwordMsg").html(json.password);
                $(".confMsg").html(json.password_confirmation);
                jQuery('.loader').hide();
            }

        },

        error: function (error) {

            console.log(error);

        }

    });
}


function get_address()
{
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/checkout/get-address',
        type: 'POST',
        success: function (data) {
            $("#address_list").html(data);

        }
    });
}

function edit_address(id)
{
    localStorage.setItem('a_id',id);
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/checkout/get-address-by',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            $("#address_body").html(data);
            $("#myAddressEdit").modal("show");
        }
    });

}

$(".delivery_type").click(function()
{ 
    localStorage.setItem('delivery_type',$(this).attr('data-id'));
	var d_address= $("input[name='d_address']:checked").val();
    var withdraw_wallet_amount = $('#withdraw_wallet_amount').val();
    var totalAmount= parseFloat($(".total_payable").text());
	jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/checkout/deliver-type',
        type: 'POST',
        data: {id:$(this).attr('data-id'),d_address:d_address,totalAmount:totalAmount},
        success: function (data) {
            if(data.status_code)
            {
               // alert(withdraw_wallet_amount);
				$(".d_charge").html(data.delivery_charge);
                var total_payable= parseFloat($(".total_payable").text());
				total_payable= total_payable + data.delivery_charge;
				$(".sub_total").text(total_payable.toFixed(2));
                $("#total_sum").val(total_payable.toFixed(2));
                /*if(withdraw_wallet_amount > 0){
                    $("#withdraw_wallet_amount").val(total_payable - withdraw_wallet_amount);
                }*/

                //$("#deliver_here_change").css("display","block")
            }
        }
    });
});

function deliver_here(city_id,id)
{
 
	var delivery_type= localStorage.getItem('delivery_type');
    var totalAmount= parseFloat($(".total_payable").text());
    localStorage.setItem('address',id);
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/checkout/deliver-here',
        type: 'POST',
        data: {id:id,delivery_type:delivery_type,city_id:city_id,totalAmount:totalAmount},
        success: function (data) {
            if(data.status_code)
            {
				
                $(".d_charge").html(data.delivery_charge);
                var total_payable= parseFloat($(".total_payable").text());
				total_payable= total_payable + data.delivery_charge;
				$(".sub_total").text(total_payable.toFixed(2) );
                $("#total_sum").val(total_payable.toFixed(2));
            }
        }
    });
}

function get_date(id)
{
    localStorage.setItem('date',id);
}

$("#process_to_payment").click(function()
{
    var time=$("input[name='time']:checked").val();
    var date= localStorage.getItem('date');
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/checkout/timeslot',
        type: 'POST',
        data: {time:time,date:date},
        success: function (data) {
            if(data.status_code)
            {
                $("#time_slot").html("");
            }
        }
    });
});
$(document).ready(function () {
//To set defauly value.
    localStorage.setItem('delivery_type','standard');
//Set current date.
    var tdate = new Date();
    var dd = tdate.getDate(); //yields day
    var MM = tdate.getMonth(); //yields month
    var yyyy = tdate.getFullYear(); //yields year
    var currentDate= dd + "-" +( MM+1) + "-" + yyyy;
    localStorage.setItem('date',currentDate);
});
function get_delivery_type(type)
{
    localStorage.setItem('delivery_type',type);
}

$(document).ready(function () {

    $('#place_order').click(function () {
        is_login= $("#lid").val();
        var delivery_date=localStorage.getItem('date');
        var delivery_type=localStorage.getItem('delivery_type');
        var time=$("input[name='time']:checked").val();
        var express_time=$("#express_time").val();
        var withdraw_wallet_amount=$("#withdraw_wallet_amount").val();
        var net_amount=$("#net_amount").val();
        var sgst_amount=$("#sgst_amount").val();

        if(is_login==0)
        {
            $("#mySignup").modal('show');
        }
        else
        {
            var address=localStorage.getItem('address');
            if(address==null){
                $('.address-error').show();
                $('html, body').animate({
                    scrollTop: $(".checkout-step").offset().top
                }, 1000);
                return false;
            }
            if(delivery_type == 'standard'){
                if(delivery_date==null || time == null){
                    $('.address-error').hide();
                    $('.date-time-error').show();
                    $('html, body').animate({
                        scrollTop: $(".tab-content").offset().top
                    }, 1000);
                    return false;
                }
            }

            var payment= jQuery("input[name='payment_mode']:checked").val();
            if(payment === undefined){
               $('.payment-mode').show();
                $('.address-error').hide();
                $('.date-time-error').hide();
                return false;
            }
            $(".loader-div").show();
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL + '/checkout',
                type: 'POST',
                dataType: 'json',
                data: {payment_mode:payment,net_amount:net_amount,sgst_amount:sgst_amount,withdraw_wallet_amount:withdraw_wallet_amount,delivery_type:delivery_type,delivery_date:delivery_date,delivery_time:time,express_time:express_time},
                success: function (data) {
                    $(".loader-div").hide();
                    if(data.success)
                    {
                        if(data.success_code=="cod")
                        {
                            jQuery('.loader').hide();
                            location.replace(BASE_URL+'/success');
                        }
                        if(data.success_code=="razorpay")
                        {
                            jQuery('.loader').hide();
                            var amount = data.amount;
                            var order_id = data.order_id;
                            if(amount > 0){
                                razorpayPayment(amount,order_id);
                            }else {
                                location.replace(BASE_URL+'/success');
                            }

                            //location.replace(BASE_URL+'/paywithrazorpay');
                        }


                    }
                    else{
                        var json=jQuery.parseJSON(JSON.stringify(data.errors));
                        console.log(json);
                    }

                },

                error: function (error) {

                    console.log(error);

                }

            });


        }
    });



});


function showPosition(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(showLocation);
    }else{ 
        $('#location').html('Geolocation is not supported by this browser.');
    }
	}


function showLocation(position){
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
	localStorage.setItem('longitude',latitude);
	localStorage.setItem('lattitude',longitude);
    $.ajax({
		 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
        type:'POST',
        url: BASE_URL + '/checkout/detect',
        data:{latitude:latitude,longitude:longitude},
        success:function(msg){
        $(".address_detect").val(msg.msg);  
        }
    });
}
    