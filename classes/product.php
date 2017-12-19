<?php 
class product_data {
	/*
	 * Show additional parameters in product page
	 */
	function show_param ($id, $arr_data, $params) 
	{
		foreach ($arr_data as $k => $v) {
			echo $v['name'].'<br />';
	
			if ( isset( $params[$id][$v['id']] ) ) $value = $params[$id][$v['id']];
			else $value = 0;
			echo '<input name="param['.$id.']['.$v['id'].']" type="text" class="sm-input" value="'.$value.'"><br />';			
		}
	}


	/*
	 * show category list
	 */
	function get_cat_list_select($str = '') 
	{
		global $wpdb;
		$cat_arr = explode(',', $str);
		
		$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat ORDER BY `name`');
		$html = '<select name="scat_cat[]" multiple size="6">';
		foreach ($arr as $key => $value) {
			$value->parent == 0 ? $subcat_txt = '' : $subcat_txt = '- ';
			if ( in_array($value->id, $cat_arr) ){
				$html .= '<option value="'.$value->id.'" selected>'.$subcat_txt.$value->name.'</option>';
			}
			else {
				$html .= '<option value="'.$value->id.'">'.$subcat_txt.$value->name.'</option>';
			}
		}
		$html .= '<option value=""></option>';
		$html .= '</select>';
	
		return $html;
	}


	/*
	 * upload product photo
	 */
	function upload_photo($files, $dir, $id)
	{
		$error = 0;

		//load photo
		if ($files['photo']['size'] > 1024*1024) {
			$html_error = __( 'File size exceeds 1024 x 1024', 'file_exceeds' ).'<br />';
			$error = 1;
			echo __( 'Error loading file', 'file_load_error' );
			return 0;
		}

		if ( $error == 0 ) {
			//create upload directory
			if (!file_exists($dir['basedir'].'/scat_products/') ) {
				mkdir($dir['basedir'].'/scat_products/');
			}
			if (!file_exists($dir['basedir'].'/scat_products/'.$id) ) {
				mkdir($dir['basedir'].'/scat_products/'.$id);
			}

			//delete old file and rename photo
			if ( file_exists($dir['basedir'].'/scat_products/'.$id.'/'.$files['photo']['tmp_name']) ) {
				unlink($dir['basedir'].'/scat_products/'.$id.'/'.$files['photo']['tmp_name']);
			} 
			else {
				move_uploaded_file($files['photo']['tmp_name'], $dir['basedir'].'/scat_products/'.$id.'/'.$files['photo']['name']);
				echo '<div class="info-message">';
				echo __( 'File upload', 'file_upload' );
				echo '</div>';
			}
			return 1;
		}
	}


	/*
	 * get category name
	 */
	function get_cat_name($str = '') 
	{
		global $wpdb;
		$str = substr($str, 0, -1);
		$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`IN ('.$str.')');

		$str = '';
		foreach ($arr_data as $key => $value) {
			$str .= $value->name . ', ';
		}
		$str = substr($str, 0, -2);
		return $str;
	}


	/*
	 * get categories name array
	 */
	function get_cat_name_arr($str = '') 
	{
		global $wpdb;
		$str = substr($str, 0, -1);
		$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`IN ('.$str.')');

		return $arr_data;
	}


	/*
	 * show product images
	 */
	function get_pr_images($id = '', $dir) 
	{
		if ( file_exists($dir['basedir'].'/scat_products/'.$id) ) {
			if ($handle = opendir($dir['basedir'].'/scat_products/'.$id) ) {	
			    while ($entry = readdir($handle)) {
			    	if ( $entry != '.' && $entry != '..' ) {
					?>
					<div class="img-wrap">
					<a href="admin.php?page=sproducts&action=edit&id=<?php echo $id; ?>&del_img=<?php echo $entry; ?>" onclick="confirm('<?php echo __( 'Delete image?', 'delete image?' ); ?>')">&#10005;</a>
					<img src="<?php echo $dir['baseurl'].'/scat_products/'.$id.'/'.$entry; ?>" alt="<?php echo $entry; ?>" title="<?php echo $entry; ?>" />
					</div>
					<?php
					}
			    }
			    closedir($handle);
			}
		}
	}


	/*
	 * get input list
	 */
	function get_param_list($name = '', $arr) 
	{
		$count = 0;
		//show values
		for ($i = 0; $i < sizeof($arr); $i++) {
			if ( $arr[$i] != '' ) {
				echo '<input name="'.$name.'[]" type="text" value="'.$arr[$i].'" class="sm-input" />';
			}
		}
		//get empty input
		for ($i = 0; $i < 5; $i++) {
			echo '<input name="'.$name.'[]" type="text" value="" class="sm-input" />';
		}
	}


	/*
	 * get color list
	 */
	function get_color_list($name = '', $arr) 
	{
		$count = 0;
		//show values
		for ($i = 0; $i < sizeof($arr); $i++) {
			if ( $arr[$i] != '' ) {
				echo '<input name="'.$name.'[]" value="'.$arr[$i].'" class="jscolor {required:false}" />';
			}
		}
		//get empty input
		for ($i = 0; $i < 5; $i++) {
			echo '<input name="'.$name.'[]" value="" class="jscolor {required:false}" />';
		}
	}



	/*
	 * get input list for frontend page
	 */
	function get_param_list_fr($name = '', $arr) 
	{
		$count = 0;
		//show values
		$html = '<ul>';
		for ($i = 0; $i < sizeof($arr); $i++) {
			if ( $arr[$i] != '' ) {
				$html .= '<li>'.$arr[$i].'</li>';
			}
		}
		$html .= '</ul>';
		return $html;
	}


	/*
	 * get color list for frontend page
	 */
	function get_color_list_fr($name = '', $arr) 
	{
		$count = 0;
		//show values
		$html = '<ul>';
		for ($i = 0; $i < sizeof($arr); $i++) {
			if ( $arr[$i] != '' ) {
				$html .= '<li style="background-color:#'.$arr[$i].'"></li>';
			}
		}
		$html .= '</ul>';
		return $html;
	}


	/* 
	 * create parameters array from DB `parameters`
	*/
	function get_product_param( $per_id = '', $show_all = '' )
	{
		global $wpdb;
		if ( $show_all == '' ) {
			$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_param` WHERE `parent` IN ('.$per_id.') ORDER BY `sort_order`');
		}
		else {
			$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_param` WHERE `parent` IN ('.$per_id.') ORDER BY `name`');
		}
		foreach ( $arr as $k => $v ) {
			$arr_val[$v->id]['id'] = $v->id;
			$arr_val[$v->id]['name'] = $v->name;
			$arr_val[$v->id]['sort_order'] = $v->sort_order;
			$arr_val[$v->id]['parent'] = $v->parent;
			$arr_val[$v->id]['hide'] = $v->hide;
			$arr_val[$v->id]['type'] = $v->type;
			
			$arr_val[$v->id]['child'] = self::get_product_param($v->id, 'show_all');
		}
		return $arr_val;
	}

}
?>