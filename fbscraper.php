<?php 
/*
Plugin name: APS FB Scraper
Version: beta 1.0
Description: This is a simple Fb ID Scraper from FB Comment Box
Author: Anwar Saludsong 
Author URI: http://apsaludsonglabs.com
Plugin URI: http://apsaludsonglabs.com
*/

class fb_scraper_obj 
{
       
	  /*===================================================================
	   *    : fbinit()
	   *    : Initialization happens here
	   *    ---------------------------------------------------------------
	   *    : @Parameters  n/a
	   *===================================================================*/
	   public static function fbinit()
	   {
	   		add_action('admin_menu', array( __CLASS__, 'fbscrape'));
			add_filter('the_content', array( __CLASS__, 'add_post_content'));
	   }
	   
	   /*===================================================================
		*    : fbscrape()
		*    : create page links
		*    ---------------------------------------------------------------
		*    : @Parameters  n/a		
		*===================================================================*/
		public static function fbscrape()
		{  
			
			$page_title = 'FB Id Scraper';
 			$menu_title = 'FB Scraper';
			$capability = 'administrator';
			$menu_slug = 'fb_mngr';

			add_object_page( $page_title, 
	        	             $menu_title, 
					         $capability, 
					         $menu_slug,
					         array( __CLASS__, 'FB_Scraper') );
	
	        add_submenu_page($menu_slug, 
			                 $page_title,  
							 'Settings', 
							 $capability,
                             'scraper_settings',
							 array( __CLASS__, 'fbscraper_settings'));
							 		
			wp_register_style("fb-style", plugins_url( '/fb-comment/style/fbscraper.css' ) );
		    wp_enqueue_style('fb-style');			 
			wp_enqueue_script("fb-js", plugins_url( '/fb-comment/script/fbjs.js' ) );
		}

		/*===================================================================
		 *    : fbscraper_settings()
		 *    : Settings and Selections of page or post
		 *    ---------------------------------------------------------------
		 *    : @Parameters  n/a		 
		 *===================================================================*/
		public static function fbscraper_settings() {
			
			echo '<h1>FB Settings</h1>';
			
			$page_x = get_option("page_selections");			
				    
			$post_x = get_option("post_selections");
			
			?>
			  
			<div id="settings_container">
				
				<div id="inner-set">
				
					<p><h4>Note: This is a selection of Page/Post to where FB Comment will be displayed from public view after the content.</h4></p>
					
					<h3 class="h3">Page FB Comment Settings</h3>
						
						<div class="table">
							<label>
									<div class="col-btn"><input type="radio" value="all" id="apage" name="page_select" <?php echo self::seld('allpage'); ?>></div>
									<div class="col-text">Appear in All Pages</div>
							</label>
							<label>
									<div class="col-btn"><input type="radio" value="spec" id="spage" name="page_select"  <?php echo self::seld('specific_page'); ?>></div>
									<div class="col-text">Select for specific pages</div>
							</label>
							<label>
									<div class="col-btn"><input type="radio" value="none" id="npage" name="page_select"  <?php echo self::seld('no_page'); ?>></div>
									<div class="col-text">Select None</div>
							</label>							
							
							<div class="hidden-conts"  <?php echo ($page_x != "none" && $page_x != "all") ? "style='display:block'" : "display:none"; ?> >
								<div class="hidden-inner">
								<?php $data1 = self::get_alldata('page',""); 
										foreach($data1 as $key => $val){
											echo self::display_data($key,$val,'page_check');
										}
								?>
								</div>
							</div>
						</div>

					<h3 class="h3">Post FB Comment Settings</h3>

						<div class="table">
							<label>
									<div class="col-btn"><input type="radio" value="all" id="apost" name="post_select" <?php echo self::seld('allpost'); ?>></div>
									<div class="col-text">Appear in All Posts.</div>
							</label>
							<label>
									<div class="col-btn"><input type="radio" value="spec" id="spost" name="post_select" <?php echo self::seld('specific_post'); ?>></div>
									<div class="col-text">Select for specific posts</div>														
							</label>
							<label>
									<div class="col-btn"><input type="radio" value="none" id="npost" name="post_select" <?php echo self::seld('no_post'); ?>></div>
									<div class="col-text">Select None</div>														
							</label>

							<div class="hidden-conts" <?php echo ($post_x != "none" && $post_x != "all") ? "style='display:block'" : "display:none"; ?> >
								
								<div class="hidden-inner">
								<?php $data1 = self::get_alldata('post',""); 
										foreach($data1 as $key => $val){
											echo self::display_data($key,$val,'post_check');
										}
								?>
								</div>

							</div>
						
						</div>
				
				</div>
				
				<input type="button" value="Save" id="save_data" class="button-primary">
				
				<div id="remarks"></div>
				
			</div>  
		  
		<?php
		}

		/*===================================================================
		 *    : FB_Scraper()
		 *    : display selections and scrape process
		 *    ---------------------------------------------------------------
		 *    : @Parameters  n/a
		 *===================================================================*/
		public static function FB_Scraper() {
			
			echo '<h1>FB Scraper</h1>';
			
			$page_x = get_option("page_selections");			
				    
			$post_x = get_option("post_selections");?>
			
			<form method="post" action="../wp-content/plugins/fb-comment/objects/csv.php"> 
			  
			<div id="settings_container">
				
				<div id="inner-set">
				
					<p><h4>Note: This is a selection of Page/Post to where FB User's ID will be scraped.</h4></p>
					
					<h3 class="h32">Select From Pages</h3>
						
						<div class="table">
							
							<div class="hidden-conts"  <?php echo (($page_x != "none" && $page_x != "all") || $page_x == "all" ) ? "style='display:block'" : "display:none"; ?> >
							    
								<div class="hidden-inner">
								
								<div class="cont-select"><div class="inp"><input id="all_page_select" type="checkbox" value=""></div><div class="title_check">Select All</div></div>
								 
								<?php $data1 = self::get_alldata('page',true); 
									  
									  if(count($data1) > 0){ 	
									  	
										foreach($data1 as $key => $val){
											echo self::display_data($key,$val,'page_check');
										}
									  
									  } else {
									  		
											echo "No fb comments where enabled.";
									  
									  }
								
								?>
								</div>
							
							</div>
							
						</div>

					<h3 class="h32">Select From Posts</h3>

						<div class="table">
							
							<div class="hidden-conts" <?php echo (($post_x != "none" && $post_x != "all") || $post_x == "all" ) ? "style='display:block'" : "display:none"; ?> >
								
								<div class="hidden-inner">
								
								<div class="cont-select"><div class="inp"><input id="all_post_select" type="checkbox" value=""></div><div class="title_check">Select All</div></div>
								
								<?php $data1 = self::get_alldata('post',true); 
									  
									  if(count($data1) > 0){ 	
										
												foreach($data1 as $key => $val){
													
													echo self::display_data($key,$val,'post_check');
												
												}

									  } else {

									  	   echo "No fb comments where enabled.";

									  }
								
								?>
								
								</div>

							</div>
						
						</div>
				
				</div>
				
				<input type="submit" value="Scrape IDs to CSV" id="scrape_id" name="scrape_id" class="button-primary">
				
			</div>
		
			</form>  
		  
		<?php
		}

		/*===================================================================
		 *    : get_alldata()
		 *    : get all post and pages with titles and id's
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $type - check if post / page
		 *      $mode - check if none, will get all data otherwise, specific
		 *===================================================================*/
		public static function get_alldata($type=null, $mode=null) {
			
			if($type != null){
				    
					$page_x = get_option("page_selections");			
				    
					$post_x = get_option("post_selections");
					
					$content_array = null;
					
					if($type == "post"){
						
							global $post;
								
							$args = array( 'numberposts' => -1);
								
							$myposts = get_posts( $args );
								
							foreach( $myposts as $post ) : setup_postdata($post); 
									
									 if($mode == null || $post_x == "all"){					 
									 
									 	$content_array[$post->ID] = get_the_title(); 					
									 
									 } else {
										 
									 	if($mode != "none" || $mode){
											
												if(strpos($post_x,"-") > 0){ 
													
													$post_ = explode("-",$post_x); 
													
													if(in_array($post->ID, $post_)) { 
														
														$content_array[$post->ID] = get_the_title(); 
													
													} 
												
												} else
								
												{ if($post_x == $post->ID) $content_array[$post->ID] = get_the_title();  }
										}
									 }
									 
							endforeach; 
							
							return $content_array;
					
					} elseif ($type == "page"){
						
							 $pages = get_pages(); 
  							 
							 foreach ( $pages as $page ) {
								 
								 	 if($mode == null || $page_x == "all"){					 
									 
									 	$content_array[$page->ID] = $page->post_title;					
									 
									 } else {
									 	if($mode != "none" || $mode){
											
												if(strpos($page_x,"-") > 0){ 
													
													$page_ = explode("-",$page_x); 
													
													if(in_array($page->ID, $page_)) { 
														
														$content_array[$page->ID] = $page->post_title; 
													} 
												
												} else
								
												{ if($page_x == $page->ID) $content_array[$page->ID] = $page->post_title;  }
										}
									 }
							}
							
							return $content_array;
					}
			
			} else {
				
				return false;
			
			}
			
		}

		/*===================================================================
		 *    : display_data()
		 *    : display checkboxes
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $key - this is the actual ID of page/post
		 *      $val - the title of page/post
		 *      $type - write if this is for page || post  			 
		 *===================================================================*/
		public static function display_data($key=null,$val=null,$type=null){
			
			return 	'<div class="cont-select"><div class="inp"><input name="'.$type.'[]" class="'.$type.'" type="checkbox" value="'.$key.'" '.self::seld($type,$key).'></div><div class="title_check">'.$val.'</div></div>';
			
		}
		
		/*===================================================================
		 *    : seld()
		 *    : get selected checkboxes as checked
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $option - this is to check if for all post/page || specific post/page || none
		 *      $id - id of specific page  		 
		 *===================================================================*/
		public static function seld($option=null,$id=null){
				
				$page_x = get_option("page_selections");			
				$post_x = get_option("post_selections");
				
				$return = "";
				
				if($option == "allpost"){
					
					if($post_x == "all"){
						$return = "checked=checked";		
					} 
					return $return;

				} elseif ($option == "specific_post"){

					if($post_x != "all" && $post_x != ""){
						$return = "checked=checked";		
					} 
					return $return;
				
				} elseif($option == "allpage"){
					
					if($page_x == "all"){
						$return = "checked=checked";		
					} 
					return $return;

				} elseif ($option == "specific_page"){

					if($page_x != "all" && $page_x != ""){
						$return = "checked=checked";		
					} 
					return $return;
				
				} elseif ($option == "page_check"){

					if($page_x != "all" && $page_x != ""){
						if(strpos($page_x,"-") > 0){ $page_x = explode("-",$page_x); if(in_array($id, $page_x)) { $return = "checked=checked"; } } else
						{ if($page_x == $id) $return = "checked=checked";  }
					} 
					return $return;
				
				} elseif ($option == "post_check"){

					if($post_x != "all" && $post_x != ""){
						if(strpos($post_x,"-") > 0){ $post_x = explode("-",$post_x); if(in_array($id, $post_x)) { $return = "checked=checked"; } } else
						{ if($post_x == $id) $return = "checked=checked"; }
					} 

					return $return;
				
				} elseif ($option == "no_post"){

					if($post_x == "none"){
						$return = "checked=checked"; 
					} 
					return $return;
				
				} elseif ($option == "no_page"){

					if($page_x == "none"){
						$return = "checked=checked"; 
					} 
					return $return;
				
				}
				
			
		}	
		
		/*===================================================================
		 *    : add_post_content()
		 *    : this will select the specifc page and post to insert 
		 *      the fb comment
		 *    ---------------------------------------------------------------
		 *    : @Parameters 
		 *      $content - WP content    
		 *===================================================================*/	
		public static function add_post_content($content) {
			
			$page_opt = get_option('page_selections');
		
			$post_opt = get_option('post_selections');
			
			if(!is_home() && !is_feed()){
				
				if($post_opt != ""){
					
					if(strpos($post_opt,"-") > 0){ 
						
						$post_arr = explode("-",$post_opt);
						
						foreach($post_arr as $p){
								
							if(is_single($p)){
								$permalink = get_permalink( $p );
								$content .= self::fb_com($permalink);
							}
						
						}
					
					} else {
						
						if(is_single($post_opt)){
							$permalink = get_permalink( $post_opt );
							$content .= self::fb_com($permalink);
						}
					
					}
		
				}
		
				if($page_opt != ""){
		
					if(strpos($page_opt,"-") > 0){ 
						
						$page_arr = explode("-",$page_opt);
						
						foreach($page_arr as $p){
								
							if(is_page($p)){
								$permalink = get_permalink( $p );
								$content .= self::fb_com($permalink);
							}
						
						}
					
					} else {
						
						if(is_page($page_opt)){
							
							$permalink = get_permalink( $page_opt );
							$content .= self::fb_com($permalink);
					
						}
					
					}
		
				}
			
			}
			
			return $content;
		
		}


		/*===================================================================
		 *    : fb_com
		 *    : this is to return fb comment code in contents
		 *    ---------------------------------------------------------------
		 *    : @Parameters 
		 *      $perm - permalinks of specific page/post from WP	
		 *===================================================================*/	
		public static function fb_com($perm){
		
				$val = '<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, "script", "facebook-jssdk"));</script>
				<div class="fb-comments" data-href="'.$perm.'" data-width="470" data-num-posts="10"></div>';
		
				return $val;
				
		}	

}

/* initialize call */
fb_scraper_obj::fbinit();?>