/**
 * Created by wingstud on 10/8/17.
 */
$(function() {
	$.validator.addMethod("uploadcheck", function(value, element) {
  return $('input[name="' + element.name + '"]').val()=="";
}, "Atleast 1 must be selected");


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

    $("form[name='add_catalog']").validate({
        rules: {
            description: {
                required: true,
                   }, 
				   
				   category: {
                      required: true,
                   }, 
				   name: {
                      required: true,
                   },
				   weight: {
                      required: true,
                   },
				   
				   starting_price:{
                       required: true,
					   number: true,
                   }, 
				   sell_price:{
                      number: true,
                   },   
				   
				    
				   upload:{
                       uploadcheck: true,
                   },
				   
				   
				   
        },
        // Specify validation error messages
        messages: {
            description: "Please enter description",
            category: "Please select category",
            weight: "Please enter weight",
            starting_price: {
				required:"MRP price is required",
				number:"MRP price must be numeric",
			},
			sell_price:{
				number:"Sell price must be numeric",
			},
            name: "Product name is required",
            upload: "Product image is required",
        },
		 errorElement : 'div',
         errorLabelContainer: '.errorTxt'
    });
	
	
	 $("form[name='price_change']").validate({
        rules: { 
				   starting_price: {
                      required: true,
					   number: true,
                   }, 
				  sale_price:{
                       required: true,
					   number: true,
                   }, 
				  
        },
        // Specify validation error messages
        messages: {
            starting_price: {
				required:"MRP price is required",
				number:"MRP price must be numeric",
			},
			sell_price:{
				number:"Sell price must be numeric",
			},
    
        }
    });
	
});

$("#sell_price").keyup(function()
{
   var starting_price=$("#starting_price").val();
   if(parseFloat($(this).val())>=parseFloat(starting_price))
   {
	   $("#sell_price_msg").html("Sell Price can not be greater than or equal to  MRP").css("color","red");
	   $(this).val("");
   }
   else
   {
	   $("#sell_price_msg").html("").css("color","red");
   }	   
});

