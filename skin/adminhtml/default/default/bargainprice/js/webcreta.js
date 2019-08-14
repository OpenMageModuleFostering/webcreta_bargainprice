$.noConflict();
jQuery(document).ready(function(){ 

jQuery('button.new-price').click(function(){

var login_status = getLogInStatus();
if(login_status == '1' ) {
	var product_id = getProductId();
	var product_sku = getProductSku();
	var product_name = getProductName();
	var product_price = getProductPrice();

	jQuery.ajax({
			type	: "POST",
			cache	: false,
			url	: getBaseUrl()+"newprice/index/loadform",
			data	: "product_id="+product_id+"&product_sku="+product_sku+"&product_name="+product_name+"&product_price="+product_price,
			success: function(result) {
				var content = jQuery(result).find('#newprice_form_div');
				jQuery.fancybox(content);
			}
			});
}
else { alert ("You need to Sign in or Register and then use this button to get product on your suggested price."); }
});

jQuery('button.submit-request').live('click',function(){ 

var customer_id = jQuery('#customer_id').val();
var customer_name = jQuery('#customer_name').val();
var customer_email = jQuery('#customer_email').val();
var product_id = jQuery('#product_id').val();
var product_sku = jQuery('#product_sku').val();
var product_name = jQuery('#product_name').val();
var product_price = jQuery('#product_price').val();
var new_price = jQuery('#new_price').val();
var new_message = jQuery('#new_message').val();

if ( new_price != "" && new_price != null ) {
var datastring = "customer_id="+customer_id+"&customer_name="+customer_name+"&customer_email="+customer_email+"&product_id="+product_id+"&product_sku="+product_sku+"&product_name="+product_name+"&product_price="+product_price+"&new_price="+new_price+"&new_message="+new_message ; 

jQuery.ajax({
		type	: "POST",
		cache	: false,
		url	: getBaseUrl()+"newprice/index/subform",
		data	: datastring,
		success: function(result_sub) {
			jQuery.fancybox.close();
			jQuery('.newPricebutton_message').html("Your price suggestion has been submitted. We will contact you through email.");			
		}
		});

}
else {

	alert ("Please fill Suggest Your Price field");

	}
return false;

});

});
