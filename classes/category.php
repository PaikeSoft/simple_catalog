<?php 
class category_data {
	/*
	 * get list category
	*/
	function get_list($parent = "")
	{
		global $wpdb;
		$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `parent`="'.$parent.'" ORDER BY `name`');
		if (sizeof($arr > 0)) {
			foreach ($arr as $key => $value) {
				$out_arr[$value->id] = $value;
				$out_arr[$value->id]->parent_arr = self::get_list($value->id);
			}
		}
		return $out_arr;
	}


	/*
	 * show list category
	*/
	function show_list($arr = "", $i)
	{
		$a = 1;
		if ( is_array($arr) ) {
			foreach ($arr as $key => $value) {
			?>
				<tr>
					<td><?php echo $i.'.'.$a ?></td>
					<td><?php echo $value->id; ?></td>
					<td><a href="admin.php?page=scategories&action=edit&id=<?php echo $value->id; ?>"><?php echo $value->name; ?></a></td>
					<td width="250"><?php category_data::get_parent_name($value->parent) ?></td>
					<td class="wcb_edcol">
						<a href="admin.php?page=scategories&action=delete&id=<?php echo $value->id; ?>" onclick="return confirm('<?php echo __( 'Categories', 'categories' ); ?>')">&#10005;</a>
					</td>
				</tr>
			<?php
			self::show_list($value->parent_arr, $i.'.'.$a);
			$a++;
			}
		}
	}


	/*
	 * get parent category
	*/
	function get_parent($id = "", $parent = "")
	{
		global $wpdb;
		
		echo '<select name="parent">';
		if ($parent != '') {
			$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`="'.$parent.'"');
			if ( sizeof($arr) > 0 ) {
				echo '<option value="'.$arr[0]->id.'">'.$arr[0]->name.'</option>';
			}
		}
		echo '<option value=""></option>';
		if ( $id != '' ) {
			$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`<>"'.$id.'" AND `id`<>"'.$parent.'" ORDER BY `name`');
		}
		else {
			$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat ORDER BY `name`, `parent`');
		}

		foreach ($arr as $key => $value) {
			echo '<option value="'.$value->id.'">'.$value->name.'</option>';
		}
		echo '</select>';
	}


	/*
	 * get parent category name
	*/
	function get_parent_name($parent = "")
	{
		global $wpdb;
		$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`="'.$parent.'"');
		echo $arr[0]->name;

	}


	/*
	 * upload category photo
	 */
	function upload_photo($files, $dir, $id)
	{
		$error = 0;

		//load photo
		if ($files['photo']['size'] > 2000*2000) {
			$error = 1;
			echo '<div class="info-message error-load">';
			echo __( 'Error. File size exceeds 2000 x 2000', 'file_exceeds' );
			echo '</div>';
			return 1;
		}

		if ( $error == 0 ) {
			//create upload directory
			if (!file_exists($dir['basedir'].'/scat_category/') ) {
				mkdir($dir['basedir'].'/scat_category/');
			}
			if (!file_exists($dir['basedir'].'/scat_category/'.$id) ) {
				mkdir($dir['basedir'].'/scat_category/'.$id);
			}

			//delete old file and rename photo
			if ( file_exists($dir['basedir'].'/scat_category/'.$id.'/'.$files['photo']['name']) ) {
				unlink($dir['basedir'].'/scat_category/'.$id.'/'.$files['photo']['name']);
			} 
			move_uploaded_file($files['photo']['tmp_name'], $dir['basedir'].'/scat_category/'.$id.'/'.$files['photo']['name']);
			echo '<div class="info-message">';
			echo __( 'File upload', 'file_upload' );
			echo '</div>';
			return 1;
		}
	}


	/*
	 * show category images
	 */
	function get_cat_images($id = '', $dir, $frontend = '') 
	{
		if ( file_exists($dir['basedir'].'/scat_category/'.$id) ) {
			if ($handle = opendir($dir['basedir'].'/scat_category/'.$id) ) {	
			    while ($entry = readdir($handle)) {
			    	if ( $entry != '.' && $entry != '..' ) {
					?>
					<div class="img-wrap">
					<?php if ($frontend == '') { ?>
					<a href="admin.php?page=scategories&action=edit&id=<?php echo $id; ?>&del_img=<?php echo $entry; ?>" onclick="confirm('<?php echo __( 'Delete image?', 'delete image?' ); ?>')">&#10005;</a>
					<?php } ?>
					<img src="<?php echo $dir['baseurl'].'/scat_category/'.$id.'/'.$entry; ?>" alt="<?php echo $entry; ?>" title="<?php echo $entry; ?>" />
					</div>
					<?php
					}
			    }
			    closedir($handle);
			}
		}
	}
}
?>