/**

 * Created by sonukumar.singh on 1/19/2017.

 */

$(function() {



    // only alphanumeric characters allowed



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

    $("#login-form").validate({

        rules: {
            login_mobile: {
                required: true,
                maxlength:10,
                number:true,
            },
        },

        messages: {
            login_mobile: {
                maxlength: "Mobile no is not greater than 10"
            }
        },
        submitHandler: function (form) {
        }
    });
});

$(document).ready(function () {

    $('#login-form').validate();

    $('#login_user').click(function () {

        if ($("#login-form").valid()) {

            jQuery('.loader-div').show();

            var mobile=jQuery("#login_mobile").val();
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL + '/login-user',
                type: 'POST',
                dataType: 'json',
                data: {mobile:mobile},
                success: function (data) {
                    if(data.error)
                    {
                        var successContent = "<div id='success-alert' class='alert alert-danger'>"+data.message+"</div>";
                        $('.auth_msg').html(successContent);
                        $("#success-alert").fadeTo(1000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);
                        });
                    }

                    if(data.success)
                    {
                        $("#login_section").hide();
                        $("#login_otp_section").show();

                    }
                    jQuery('.loader-div').hide();
                },
                error: function (error) {
                    console.log("Something wrong in username and password");
                }

            });

        }

    });



});



function verify_login_otp()
{
    var otp=jQuery("#login_otp").val();
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/verify-login-otp',
        type: 'POST',
        dataType: 'json',
        data: {otp:otp},
        success: function (data) {
            if(data.success)
            {
                $("#otp").val('');
                var successContent = "<div id='success-alert' class='alert alert-success'>Login successfully.</div>";
                $('.auth_msg').html(successContent);
                $("#success-alert").fadeTo(1000, 100).slideUp(100, function(){
                    $("#success-alert").slideUp(100);

                });
                location.reload();
            }
            if(data.fail)
            {

                var successContent = "<div id='success-alert' class='alert alert-danger'>"+data.message+"</div>";
                $('.auth_msg').html(successContent);
                $("#success-alert").fadeTo(1000, 100).slideUp(100, function(){
                    $("#success-alert").slideUp(100);
                });

            }
        }
    });
}

$(".login_form").keydown(function(event){

    if (event.which == 13) {

        $("#login_user").trigger('click');

    }

});





function showLogin()

{

    var clogin = $("#content-login");

    var cregister = $("#content-register");

    var newheight = clogin.height();

    $(clogin).css("display", "block");



    $(clogin).stop().animate({

        "left": "0px"

    }, 800, function() { /* callback */ });

    $(cregister).stop().animate({

        "left": "880px"

    }, 800, function() { $(cregister).css("display", "none"); });



    $("#page").stop().animate({

        "height": newheight+"px"

    }, 550, function(){ /* callback */ });

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

}/**

 * Created by sonukumar.singh on 1/20/2017.

 */



function ChangeTypeLogin()

{

    if($("#login_password").attr('type')=="text") {



        $("#login_password").attr("type",'password');

    }

    else

    {

        $("#login_password").attr("type",'text');

    }

}

function reset_password()
{
    jQuery('.loader-div').show();
    var email=jQuery("#remail").val();
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/reset-password-verify',
        type: 'POST',
        dataType: 'json',
        data: {email:email},
        success: function (data) {
            jQuery('.loader-div').hide();
            if(data.success)
            {
                $("#remail").val('');
                var successContent = "<div id='success-alert' class='alert alert-success'>Reset password link has been sent to your email</div>";
                $('.rsuccessMsg').html(successContent);
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                });
            }
            if(data.error)
            {
                console.log(data.error);
                //var json=jQuery.parseJSON(JSON.stringify(data.errors));
                $("#otp_msg").html("Invalid Otp");
                //jQuery('.loader').hide();
            }
        }
    });
}

