$.noConflict();
jQuery(document).ready(function(){ 
	
	jQuery('button.new-price').click(function(){
		
		var login_status = getLogInStatus();
		if(login_status == '1' ) {
			var product_id = getProductId();
			var product_sku = jQuery('#sku-container').html();
			var product_name = getProductName();
			var product_price = jQuery('.price-box .price').html().replace(/,/g, "");
			
			jQuery.ajax({
				type	: "POST",
				cache	: false,
				url	: getBaseUrl()+"bargainprice/index/loadform",
				data	: "product_id="+product_id+"&product_sku="+product_sku+"&product_name="+product_name+"&product_price="+product_price,
				success: function(result) {
					
					var content = jQuery(result).find('#bargainprice_form_div');
					jQuery.fancybox(content);
				}
			});
		}
		else { 
			var newPriceUrl = getCustomerAccount();
			var msg = "You Have to Login First...." +
			"Do You Want to Login?";
			
			if(confirm(msg) ){			
				window.location.href = newPriceUrl; 
			}
		}
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
				url	: getBaseUrl()+"bargainprice/index/subform",
				data	: datastring,
				success: function(result_sub) {
					
					jQuery.fancybox.close();
					window.location.reload();
				},
				
				error:function(){
					
					jQuery.fancybox.close();
					window.location.reload();
					
				},
			});
			
		}
		else {
			alert ("Please fill Suggest Your Price field");
		}
		return false;
		
	});
});
