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
		 *===================================================================*/		
		public static function download_csv($FileName=null,$pages=null,$posts=null) {
			    
				$Content = self::set_content($pages,$posts);
				
				self::set_headers($FileName,$Content);
				
				# print csv
				exit( $Content );
				
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
				
				header('Content-Type: application/csv');
				header('Content-Length: '.strlen($Content));
                header('Content-Disposition: attachment; filename='.$File);
				header('Pragma: no-cache');
				
				# clean previous header
				ob_end_clean();
		
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
				
				foreach($pages as $pag){
					$arr_links[] = get_permalink($pag);
				}

				foreach($posts as $pos){
					$arr_links[] = get_permalink($pos);
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
											if ($key == "from"){
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
	$pages = $_POST['page_check'];
	$posts = $_POST['post_check'];
	
	CSV::download_csv("id_scrape.csv",$pages,$posts);
	die();

?>