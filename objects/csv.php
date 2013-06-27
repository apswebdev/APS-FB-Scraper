<?php

/*===================================================================
 *    : CSV()
 *    : downloadable for csv 
 *    : Anwar Saludsong 
 *    : http://apsaludsonglabs.com
 *    ---------------------------------------------------------------
 *    : @Parameters  n/a		 
 *===================================================================*/	

#include dependent WP Load
include_once("../../../../wp-load.php");

class CSV{
	
		 
		 protected static $actual_id = array();

		/*===================================================================
		 *    : download_csv()
		 *    : initial download 
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $FileName - this is the filename for output/download	
		 *      $pages - page IDS
		 *      $posts - post IDS	 
		 *      $url - outside URL 
		 *===================================================================*/		
		public static function download_csv($FileName=null,$pages=null,$posts=null,$url=null) {
			    
				# For URL Scrape
				if(!empty($url)){
					
						if(self::validate_url($url)){
					
								self::get_fb_ids($url);
		
								$Content = self::set_csv_content();
								
								if(!empty($Content)){
								
										self::set_headers($FileName,$Content);
										
										exit( $Content );
								
								} 
						
						} else {
							
							    exit("Invalid URL");
							
						}
				
				# For Self Scrape ( whithin website )
				} else {
				
						$Content = self::set_content($pages,$posts);
						
						if(!empty($Content)){
						
								self::set_headers($FileName,$Content);
								
								exit( $Content );
						
						} 
				
				}
				
				exit("No FB User IDs Found");
				
		}

		/*===================================================================
		 *    : set_headers()
		 *    : this is for setting up force download CSV headers 
		 *    ---------------------------------------------------------------
		 *    : @Parameters 
		 *      $File - Filename for CSV ouput
		 *      $Content - CSV Content
		 *===================================================================*/			
		protected static function set_headers($File=null, $Content=null){
				
				# alternative header setup
				# header("Pragma: public");
                # header("Expires: 0");
                # header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                # header("Cache-Control: private", false);
                # header("Content-Type: application/octet-stream");
				# header('Content-Type: application/csv');
				# header('Content-Length: '.strlen($Content));
                # header("Content-Disposition: attachment; filename='".$FileName."';" );
                # header("Content-Transfer-Encoding: binary");
				
				@header('Content-Type: application/csv');
				@header('Content-Length: '.strlen($Content));
                @header('Content-Disposition: attachment; filename='.$File);
				@header('Pragma: no-cache');
				
				# clean previous header
				ob_end_clean();
		
		}
		

		/*===================================================================
		 *    : validate_url()
		 *    : this is for validating url 
		 *    ---------------------------------------------------------------
		 *    : @Parameters 
		 *      $url - Url Input
		 *===================================================================*/			
		protected static function validate_url($url=null){
			
			    if (filter_var($url, FILTER_VALIDATE_URL) != false){

						$pattern_1 = "/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
						$pattern_2 = "/^(www)((\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
						$pattern_3 = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";       
						
						if(preg_match($pattern_1, $URL) || preg_match($pattern_2, $URL)){
							
							if( preg_match($pattern_3, $URL)){
								return true;
							}
							
						} 
				}
	  			return false;
 		
		}		
		
		/*===================================================================
		 *    : set_content()
		 *    : initial download 
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $FileName - this is the filename for output/download		 
		 *===================================================================*/				
		protected static function set_content($pages=null,$posts=null){
			    
				$links = array();
				
				$links = self::get_links($pages,$posts);
				
				$ids = array();
				
				foreach($links as $l){
					
					self::get_fb_ids($l);

				}
				
				$Content = self::set_csv_content();
				
				return $Content;
		
		}

		/*===================================================================
		 *    : get_links()
		 *    : get_permalinks 
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $pages - page IDS
		 *      $posts - post IDS	 
		 *===================================================================*/				
		protected static function get_links($pages=null,$posts=null){
				
				$arr_links = array();
				if(!empty($pages)){
						foreach($pages as $pag){
							$arr_links[] = get_permalink($pag);
						}
				}

				if(!empty($posts)){
						foreach($posts as $pos){
							$arr_links[] = get_permalink($pos);
						}
				}
				return $arr_links;
			
		}

		/*===================================================================
		 *    : get_fb_ids()
		 *    : get_permalinks 
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $plinks - WP permalinks of page/post
		 *===================================================================*/				
		protected static function get_fb_ids($plinks=null){
				
				# fb link to get fb comment part for users data as returned
				$json = file_get_contents("https://graph.facebook.com/comments/?ids=".$plinks);
				
				$arr = array();
				
				$fb_response = json_decode($json, true);
				
				foreach($fb_response as $key => $value){
					foreach($value as $v){
						foreach($v as $k => $x){
							if ($k == "data"){
									foreach($x as $data){
										foreach($data as $key=> $value){
											if ($key == "from" && $value != null){
												foreach($value as $in => $v_in){
													if($in == "id"){
														if(!in_array($v_in, self::$actual_id)){
															self::$actual_id[] = $v_in;
														}
													}
												}
											}
											if ($key == "comments" && $value != null){
												foreach( $value as $k1 => $v1){
													if ($k1 == "data"){
														    foreach($v1 as $d4){
																foreach($d4 as $key4=> $value4){
																	if ($key4 == "from" && $value4 != null){
																		foreach($value4 as $in4 => $v_in4){
																			if($in4 == "id"){
																				if(!in_array($v_in4, self::$actual_id)){
																					self::$actual_id[] = $v_in4;
																				}
																			}
																		}
																	}
																}
															}
													}
												
												}
											}
										}
									}
							} 
							
							if ($k == "paging"){
								foreach($x as $key2 => $value2){
									if($key2 == "next"){
										self::get_paging($value2);
										break;
									}
								}
							}
						}
					}
				}
			
		}

		/*===================================================================
		 *    : et_paging()
		 *    : get the next comments in the comment box 
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $url - actual FB json file api link
		 *===================================================================*/				
		protected static function get_paging($url=null){
				
				# fb link to get fb comment part for users data as returned
				$json = file_get_contents($url);
				
				$arr = array();
				
				$fb_response = json_decode($json, true);
				
				foreach($fb_response as $k => $x){
					if ($k == "data"){
							foreach($x as $data){
								foreach($data as $key=> $value){
									if ($key == "from" && $value != null){
										foreach($value as $in => $v_in){
											if($in == "id"){
												if(!in_array($v_in, self::$actual_id)){
													self::$actual_id[] = $v_in;
												}
											}
										}
									}
								}
							}
					} 
					
					if ($k == "paging"){
						foreach($x as $key2 => $value2){
							if($key2 == "next"){
								self::get_paging($value2);
								break;
							}
						}
					}
				}
			
		}		

		/*===================================================================
		 *    : set_csv_content()
		 *    :  
		 *    ---------------------------------------------------------------
		 *    : @Parameters  
		 *      $arr - WP permalinks of page/post
		 *===================================================================*/				
		protected static function set_csv_content(){
				
				$string = "";
				
				foreach(self::$actual_id as $id){
					
					$string .= $id."\n";
					
				}
				return $string;
			
		}		
		
}


/*===================================================================
 *    : start initialization
 *    ---------------------------------------------------------------
 *    : @Parameters  n/a
 *===================================================================*/		
	$pages = (isset($_POST['page_check'])) ? $_POST['page_check'] : "" ;
	$posts = (isset($_POST['post_check'])) ? $_POST['post_check'] : "" ;
	$url_scrape = (isset($_POST['url_scrape'])) ? $_POST['url_scrape'] : "" ;
	
	CSV::download_csv("id_scrape.csv",$pages,$posts,$url_scrape);
	die();

?>