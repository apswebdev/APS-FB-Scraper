<?php
//===================================================================
//     Name : ajx_process.php
//     Programmer : Anwar Saludsong
//     website : http://apsaludsonglabs.com
//===================================================================


/*===================================================================
*    : require
*    : require dependent files
*    ---------------------------------------------------------------
*    : @Parameters  n/a
*===================================================================*/
 require_once('../../../../wp-load.php');	


/*===================================================================
*    : ajax_post
*    : Class
*    ---------------------------------------------------------------
*    : @Parameters  n/a
*===================================================================*/
class ajax_post
{
	                                                                                                                                                                                                                                                                                        
	  /*===================================================================
	   *    : init()
	   *    : process of distributing updates
	   *    ---------------------------------------------------------------
	   *    : @Parameters  
	   *	  $page - this are the actual page IDs
	   *	  $post - this are the actual post IDs
	   *===================================================================*/
		public static function init($page=null,$post=null){
		   
				$pag = self::upd_options("page_selections", $page);
				$pos = self::upd_options("post_selections", $post);
				
				if($pag || $pos){
				echo "You have Successfully Updated Your Settings!";	
				} else {
				echo "Error: Failed Process. Try Again.";	
				}
		
		}

	  /*===================================================================
	   *    : upd_options()
	   *    : process of the actual updates for WP options var
	   *    ---------------------------------------------------------------
	   *    : @Parameters  
	   *	  $option - this are the actual WP option name
	   *	  $value - this are the actual Wp option value
	   *===================================================================*/	
		public static function upd_options($option=null,$value=null){
			
		   		   
				if ( get_option( $option ) != $value ) {
				
				 	update_option( $option, $value );
				 	return true;
				
				} else {
				
				 	$deprecated = ' ';
				 	$autoload = 'no';
				 	add_option( $option, $value );
				 	return true;
				
				}	
	
		}

}

/* PROCESS Starts Here */
 $page = $_POST['page'];
 $post = $_POST['post'];
 echo ajax_post::init($page,$post);	

?>