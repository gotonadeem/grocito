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
    // onlyAlphabets with space.
    jQuery.validator.addMethod('onlyAlphabets', function (value, element) {
        if(/^[a-zA-Z\s]+$/i.test(value))
        {
            return true;
        }
    });
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
    $("#register-form").validate({
        rules: {
            fname: {
                required: true,
                onlyAlphabets: true,
                maxlength:20
            },
            lname: {
                required: true,
                onlyAlphabets: true,
                maxlength:20
            },
            mobile: {
                required: true,
                validMobile:true,
                maxlength:10
            },
            dob: {
                required: true,
            },

            user_email: {
                required: true,
                validEmail: true,
                maxlength:100
            },

        },
        messages: {
            fname: {
                maxlength: "First name cannot be longer than 20 characters",
                required:"First name is required"
            },
            lname: {
                maxlength: "Last name cannot be longer than 20 characters",
                required:"Last name is required",
            },

            mobile: {
                maxlength: "Mobile cannot be longer than 10 characters",
                required:"Mobile is required",
            },

            user_email: {
                maxlength: "Password cannot be longer than 100 characters"
            },
            dob: {
                required:"DOB is required"
            },



        },
        submitHandler: function (form) {
        }
    });

});
$(document).ready(function () {
    $("#otp_section").hide();
    $("#login_otp_section").hide();
    $('#register-form').validate();
    $('#register_user').click(function () {

        if ($("#register-form").valid()) {
            jQuery('.loader-div').show();
            //$(".loader").fadeIn("slow");
            var fname=jQuery("#fname").val();
            var lname=jQuery("#lname").val();
            var mobile=jQuery("#mobile").val();
            var email=jQuery("#user_email").val();
            var dob=jQuery("#datepicker-dob").val();
            var reff_code=jQuery("#reff_code").val();
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL + '/register',
                type: 'POST',
                dataType: 'json',
                data: {fname:fname,lname:lname,email:email,mobile:mobile,dob:dob,reff_code:reff_code},
                success: function (data) {
                    if(data.success)
                    {
                        $("#fname").val('');
                        $("#lname").val('');
                        $("#user_email").val('');
                        $("#mobile").val('');
                        $("#password").val('');
                        $("#password_confirmation").val('');
                        //$('.otp-number').val(data.otp);
                        
                        $("#register_section").hide();
                        $("#otp_section").show();

                    }
                    if(data.ref_errors){
                        $(".refErrorMsg").html('Invalid Referral Code.');
                    }
                    if(data.fail)
                    {

                        var json=jQuery.parseJSON(JSON.stringify(data.errors));
                        $(".fnameMsg").html(json.fname);
                        $(".lnameMsg").html(json.lname);
                        $(".emailMsg").html(json.email);
                        $(".mobileMsg").html(json.mobile);
                        $(".dobMsg").html(json.dob);
                        $(".genderMsg").html(json.gender);
                        $(".passwordMsg").html(json.password);
                        $(".confMsg").html(json.password_confirmation);

                    }
                    jQuery('.loader-div').hide();
                },

                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

});


function verify_otp()
{
    var otp=jQuery("#otp").val();
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/verify-otp',
        type: 'POST',
        dataType: 'json',
        data: {otp:otp},
        success: function (data) {
            if(data.success)
            {
                $("#otp").val('');
                var successContent = "<div id='success-alert' class='alert alert-success'>"+data.message+"</div>";
                $('.auth_msg').html(successContent);
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                    location.reload();
                });

            }
            if(data.fail)
            {
                var successContent = "<div id='success-alert' class='alert alert-danger'>"+data.message+"</div>";
                $('.auth_msg').html(successContent);
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                });
            }
        }
    });
}


function resend_otp()
{

    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/resend-otp-code',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if(data.success)
            {
                $("#otp").val('');
                var successContent = "<div id='success-alert' class='alert alert-success'>"+data.message+"</div>";
                $('.auth_msg').html(successContent);
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                    //location.reload();
                });

            }
            if(data.fail)
            {
                var successContent = "<div id='success-alert' class='alert alert-danger'>"+data.message+"</div>";
                $('.auth_msg').html(successContent);
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                });
            }
        }
    });
}

function changeType(value)
{
    if($("."+value).attr('type')=="text") {
        $("." + value).attr('type', 'password');
    }
    else
    {
        $("."+value).attr('type', 'text');
    }
}