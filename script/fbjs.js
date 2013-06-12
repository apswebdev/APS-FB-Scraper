// ==============================================================================
//	fbjs.js   
//  Programmer: Anwar Saludsong
//  Website: http://apsaludsonglabs.com
// ==============================================================================

jQuery(document).ready(function(){
    
	// ==============================================================================
	//	Show selections of post/page upon click of specific radio button in Settings   
	// ==============================================================================
	jQuery("#spage, #spost").click(function(){
				
				jQuery(this).parent().parent().nextAll(".hidden-conts").eq(0).show();
		
	});

	// ==============================================================================
	//	Hide selections of post/page upon click of specific radio button in Settings   
	// ==============================================================================
	jQuery("#apage, #apost, #npage, #npost").click(function(){
				
				jQuery(this).parent().parent().nextAll(".hidden-conts").eq(0).hide();
		
	});
	
	// ==============================================================================
	//	Select All or Unselect Check Boxes   
	// ==============================================================================
	jQuery("#all_page_select, #all_post_select").click(function(){
				
				if(jQuery(this).prop('checked')){
					
					if(jQuery(this).attr('id') == "all_page_select"){
						
						jQuery(".page_check").prop('checked', true);
					
					} else {
					
						jQuery(".post_check").prop('checked', true);
					
					}
					
				} else {
				
					if(jQuery(this).attr('id') == "all_page_select"){
						
						jQuery(".page_check").prop('checked', false);
					
					} else {
					
						jQuery(".post_check").prop('checked', false);
					
					}
				
				}
		
	});	

	// ==============================================================================
	//	This function is the saving for the FB Settings   
	// ==============================================================================
	jQuery("#save_data").click(function(){
	    
		var apage = "";
		var apost = "";
		
		if (jQuery("input:radio[name=page_select]:checked").val() == "all"){

			apage = "all"; 
	
		} else if (jQuery("input:radio[name=page_select]:checked").val() == "none"){ 
	    
			apage = "none"; 
		
		} 
		
		else {
			
			jQuery('.page_check:checkbox:checked').each(function(){
				apage = apage + "-" + jQuery(this).val();
			});
			
			apage = apage.substr(1);
		
		}

		if (jQuery("input:radio[name=post_select]:checked").val() == "all"){
			
			apost = "all";
	
		} else if (jQuery("input:radio[name=post_select]:checked").val() == "none"){
			
			apost = "none";
	
		} 
		
		else {

			jQuery('.post_check:checkbox:checked').each(function(){
				apost = apost + "-" + jQuery(this).val();
			});
			
			apost = apost.substr(1);
		
		}
		
		var err = check_d(apage,apost); 
		if( err !=""){
			alert(err);
		} else {
			ajax_update_options(apage,apost);
		}
		
		
	});
	
	// ==============================================================================
	//	Update data options from settings   
	// ==============================================================================
	function ajax_update_options(a,b){
	
		jQuery.ajax({
			type: 'POST',
			url: "../wp-content/plugins/fb-comment/ajax/ajx_process.php",
			data: { page: a, post: b },
			dataType: 'html',
			
			beforeSend: function() {
				jQuery('#remarks').html('Saving your settings....');
			},
			
			success: function(data) {
				jQuery('#remarks').html(data);
			},
			error: function (responseData) {
				jQuery('#remarks').html("Error: Process Failed");
			}
		});
	
	}
	
	// ==============================================================================
	//	 Checks if the selection is for specific page or post   
	// ==============================================================================
	function check_d(a,b){
		var err = "";	
		if( a == "" ){
			err = "Please select one or more from specific pages\n";
		}
		if( b == "" ){
			err = err + "Please select one or more from specific posts";
		}
	    return err;
	}
	
});