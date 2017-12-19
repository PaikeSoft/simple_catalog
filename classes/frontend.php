<?php
class frontend
{
	function slide_category()
	{
		global $wpdb;
		echo 'slide_category';
	}


	/*
	 * get list category
	*/
	function get_list($parent = "0")
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
	 * get subcategories in category
	*/
	function get_category($id = "0")
	{
		global $wpdb;
		$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`="'.$id.'" ORDER BY `name`');
		if (sizeof($arr > 0)) {
			foreach ($arr as $key => $value) {
				$out_arr[$value->id] = $value;
				$out_arr[$value->id]->parent_arr = self::get_list($value->id);
			}
		}
		return $out_arr;
	}


	/*
	 * get list of main subcategories
	*/
	function main_categories($arr = '')
	{
		global $wpdb;
		$uploads = wp_upload_dir();
		$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_mime_type`="scategory"');

		echo '<div class="scat_categories">'; 
		if ( is_array($arr) ) {
			foreach ($arr as $key=>$value) {
				//insert image
				echo '<div class="main-cat">';
				echo '<div class="cat-name">'.$value->name.'</div>';
				$dir = opendir($uploads['basedir'].'/scat_category/'.$value->id);
				while ($entry = readdir($dir)) {
					if ( $entry != '.' && $entry != '..' ) {
						echo '<div class="cat-img-wrap"><img src="'.$uploads['baseurl'].'/scat_category/'.$value->id.'/'.$entry.'" /></div>';
						break;
						closedir($dir);
					}
				}
				echo '<div class="cat-descr">'.$value->descr.'</div>';
				echo '<a href="'.get_page_link($arr_data[0]->ID).'?id='.$value->id.'" class="view_main_cat">'.__('view', 'view').'</a>';
				echo '</div>';
			}
		}
		echo '</div>';
	}


	/*
	 * get list category
	*/
	function categories($arr = '', $i)
	{
		global $wpdb;
		$uploads = wp_upload_dir();
		$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_mime_type`="scategory"');

		echo '<div class="scat_categories">'; 
		if ( is_array($arr) ) {
			foreach ($arr as $key=>$value) {
				//insert image
				if ( $value->parent == '0' ) {
					echo '<div class="main-cat">';
					echo '<div class="cat-name">'.$value->name.'</div>';
					$dir = opendir($uploads['basedir'].'/scat_category/'.$value->id);
					while ($entry = readdir($dir)) {
						if ( $entry != '.' && $entry != '..' ) {
							echo '<div class="cat-img-wrap"><img src="'.$uploads['baseurl'].'/scat_category/'.$value->id.'/'.$entry.'" /></div>';
							break;
							closedir($dir);
						}
					}
					echo '<div class="cat-descr">'.$value->descr.'</div>';
					echo '<a href="'.get_page_link($arr_data[0]->ID).'?id='.$value->id.'" class="view_main_cat">'.__('view', 'view').'</a>';
					echo '</div>';
					self::categories($value->parent_arr, 1);
				}
				else {
					echo '<div class="sub-cat">';
					if ( $i == 1 ) {
						echo '<span class="sub-cat-marker"></span> <a href="'.get_page_link($arr_data[0]->ID).'?id='.$value->id.'"><b>'.$value->name.'</b></a>';
						self::categories($value->parent_arr, 2);					
					}
					else {
						echo '- <a href="'.get_page_link($arr_data[0]->ID).'?id='.$value->id.'"><b>'.$value->name.'</b></a>';
						self::categories($value->parent_arr, 2);
					}
					echo '</div>';
				}
			}
		}
		echo '</div>';
	}


	/*
	 * create toolbar
	*/
	function toolbar($arr = '', $i, $arr_data)
	{
		global $wpdb;
		$uploads = wp_upload_dir();

		if ( is_array($arr) ) {
			foreach ($arr as $key=>$value) {
				//insert image
				if ( $value->parent == '0' ) {
					echo '<div class="scat__toolbal-mcat">';
					echo '<a href="'.get_page_link($arr_data[0]->ID).'?id='.$value->id.'" class="view_main_cat">'.$value->name.'</a>';
					echo '</div>';
					self::toolbar($value->parent_arr, 1, $arr_data);
				}
				else {
					echo '<div class="scat__toolbal-scat">';
					if ( $i == 1 ) {
						echo '<span class="sub-cat-marker"></span> <a href="'.get_page_link($arr_data[0]->ID).'?id='.$value->id.'">'.$value->name.'</a>';
						self::toolbar($value->parent_arr, 2, $arr_data);					
					}
					else {
						echo '- <a href="'.get_page_link($arr_data[0]->ID).'?id='.$value->id.'"><b>'.$value->name.'</b></a>';
						self::toolbar($value->parent_arr, 2, $arr_data);
					}
					echo '</div>';
				}
			}
		}
	}


	/*
	 * show product list
	*/
	function category($id = '')
	{
		global $wpdb;
		include 'category.php';
		if ( $id == '' ) {
			$id = $_REQUEST['id'];
		}

		$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_mime_type`="scategory"');
		?>

		<?php
		self::quick_view_template();
		self::order_popup_template();
		?>

		<?php
			//create breadcrumb
			$arr_br = self::cat_breadcrumbs($id);
			$arr_br = array_reverse($arr_br);
		?>
		<ul class="scat__br">
			<li>
				<a href="<?php echo site_url(); ?>"><?php echo __('Home', 'home'); ?></a>
			</li>
			<li class="li-arrow">&nbsp</li>
			<?php
			for ($i = 0; $i < sizeof($arr_br); $i++ ) {
				if ( $i < sizeof($arr_br)-1) {
					echo '<li><a href="'.get_page_link($arr_data[0]->ID).'?id='.$arr_br[$i]->id.'">'.$arr_br[$i]->name.'</a></li><li class="li-arrow">&nbsp</li>';
				}
				else {
					echo '<li>'.$arr_br[$i]->name.'</li>';
				}
			}
			?>
		</ul>

		<?php
		if ($id != '' ) {
			$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`="'.$id.'"');
			echo '<header class="scat__header">';
			echo '<h1 class="entry-title">'.$arr_data[0]->name.'</h1>';

			if ( $arr_data[0]->parent == 0 ) {$show_toolbar = 0;}
			else {$show_toolbar = 1;}
		}
		else {
			$show_toolbar = 1;
		}

		if ( $id != '' ){
			$dir = wp_upload_dir();
			category_data::get_cat_images($id, $dir, 'frontend');
		}
		echo '<div class="scat__descr">'.$arr_data[0]->descr.'</div>';
		echo '</header>';

		if ( $id == '' ) {
			$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products WHERE `hide`="0" ORDER BY `name`, `in_stock`');
		}
		else {
			$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products WHERE `cat_id` LIKE ("%'.$id.',%") AND `hide`="0" ORDER BY `name`,  `in_stock`');
		}
		$path = wp_upload_dir();
		$settings = self::get_settings();
		
		if ($show_toolbar == 0) {
			$arr = self::get_list($id);
			self::main_categories($arr);
		}
		else {
			$arr = self::get_list();
			echo '<div class="scat__toolbal-select">'.__('Categories', 'categories').'</div>';
			echo '<div class="scat__toolbal-cat">';
			self::toolbar($arr, 0, $arr_data);
			echo '</div>';
		}

		echo '<ul class="scat__cat">';
			foreach ($arr_data as $k=>$v) {
				?>
				<li class="scat__cat-product <?php if ($v->description != '') echo 'has-descr';?>">
					<div class="scat__cat-primage">
						<?php
							$dir = opendir($path['basedir'].'/scat_products/'.$v->id);
							$enter_img = 0;
							if ($settings['enable_pr_page'] == 1) echo '<a href="'.get_site_url().'/'.$settings['sproduct'].'?id='.$v->id.'">';
	
						    while ($entry = readdir($dir)) {
						    	if ( $entry != '.' && $entry != '..' ) {
								$enter_img = 1;
								?>
								<img src="<?php echo $path['baseurl'].'/scat_products/'.$v->id.'/'.$entry; ?>" alt="<?php echo $entry; ?>" title="<?php echo $entry; ?>" />
								<?php
								break;
								}
						    }
							?>
							<?php
							if ($enter_img == 0) {
								$url = plugins_url();
								?>
								<img src="<?php echo $url.'/scatalog/images/no_photo.svg'; ?>" alt="<?php echo __('No photo', 'no photo'); ?>" title="<?php echo $entry; ?>" />
								<?php
							}
							if ($settings['enable_pr_page'] == 1) echo '</a>';
						?>
					</div>
					<div class="scat__cat-area">
						<div class="scat__cat-prname">
							<div data-name="<?php echo $v->id ?>">
							<?php if ($settings['enable_pr_page'] == 1) echo '<a href="'.get_site_url().'/'.$settings['sproduct'].'?id='.$v->id.'">'; ?>
							<?php echo $v->name ?>
							<?php if ($settings['enable_pr_page'] == 1) echo '</a>'; ?>
							</div>
						</div>
						<?php if ($settings['enable_quick_view'] == 1) { ?>
							<div class="qv-button" data-id="<?php echo $v->id; ?>"></div>
						<?php } ?>
						<?php if ($v->new_price > 0) { ?>
						<div class="scat__cat-pricebox">
							<div class="sale-product-icon"><?php echo __('Sale', 'sale'); ?></div>
							<p class="old-price"><?php echo $settings['currency'].$v->price ?></p>
							<p class="special-price"><?php echo $settings['currency'].$v->new_price ?></p>
						</div>
						<?php } else { ?>
						<div class="scat__cat-pricebox">
							<p class="regular-price">
								<?php 
									if ( $v->price == 0 ) {
										echo '<span class="free-price">'.__('Free', 'free').'</span>';
									}
									else {
										echo $settings['currency'].$v->price;
									}
								?>
							</p>
						</div>
						<?php } ?>
						<?php if ($v->in_stock == 0) echo '<div class="scat__avaib">'.__('Out of stock', 'out of stock').'</div>'; ?>
	
						<?php if ($v->description != '') { ?>
						<div class="scat__cat-prdescr">
							<?php 
							if ( strlen($v->description) < 100 ) echo $v->description;
							else echo(substr($v->description, 0, 100).'...');
							?>
						</div>
						<?php } ?>
						<?php
						if ( $settings['enable_add_to_cart'] == 1 ) {
							echo '<button class="scat__btnorder" data-id="'.$v->id.'">'.$settings['button_text'].'</button>';
						}
						?>
					</div>
				</li>
				<?php
			}
		echo '</ul>';
		?>
		<div class="clear"></div>
		<div style="display: none;" class="data-form">
			<input type="hidden" value="<?php echo plugin_dir_url(__FILE__); ?>" class="data_site-url" />
			<input type="hidden" value="<?php echo __('In stock', 'in stock'); ?>" class="data_in-stock" />
			<input type="hidden" value="<?php echo __('Out of stock', 'out of stock'); ?>" class="data_out-of-stock" />
			<input type="hidden" value="<?php echo $settings['currency']; ?>" class="data_currency" />
			<input type="hidden" value="<?php echo $settings['store_manager_email']; ?>" class="store_manager_email" />
		</div>
		<?php
	}


	/*
	 * show product page
	*/
	function product($id = '', $arr_val)
	{
		global $wpdb;
		if ( $id == '' ) {
			$id = $_REQUEST['id'];
		}
		if ( $_REQUEST['id'] == '' ) {
			echo __('Product not found', 'product not found');
			return;
		}

		$arr_link = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_mime_type`="scategory"');

		$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products WHERE `id`="'.$id.'"');
		$v = $arr_data[0];
		$cat = explode(',', $v->cat_id);

		if ( $v->hide == 1 || !isset($arr_data[0])  ) {
			echo __('Product not found', 'product not found');
			return;
		}

		$settings = self::get_settings();

		$path = wp_upload_dir();
		?>

		<?php
			//create breadcrumb
			$arr_br = self::cat_breadcrumbs($cat[0]);
			$arr_br = array_reverse($arr_br);
			
			//new order
			self::order_popup_template();
		?>
		<ul class="scat__br">
			<li>
				<a href="<?php echo site_url(); ?>"><?php echo __('Home', 'home'); ?></a>
			</li>
			<li class="li-arrow">&nbsp</li>
			<?php
			for ($i = 0; $i < sizeof($arr_br); $i++ ) {
				echo '<li><a href="'.get_page_link($arr_link[0]->ID).'?id='.$arr_br[$i]->id.'">'.$arr_br[$i]->name.'</a></li><li class="li-arrow">&nbsp</li>';
			}
			?>
			<li>
				<?php echo $v->name; ?>
			</li>
		</ul>

		<div class="scat__pr">
			<header class="entry-header">
				<h1 class="entry-title"><?php echo $v->name ?></h1>
			</header>
			<div class="scat__pr-image">
				<?php
					$dir = opendir($path['basedir'].'/scat_products/'.$v->id);
					$enter_img = 0;

				    $img_html = '';
					$i = 1;
					$class = '';

				    while ($entry = readdir($dir)) {
				    	if ( $entry != '.' && $entry != '..' ) {
				    		$enter_img == 0 ? $class = 'class="scat__qv-imgactive"': $class = '';
							if ($i == 1) {
								echo '<div class="scat__pr-imgwrap"><img src="'.$path['baseurl'].'/scat_products/'.$v->id.'/'.$entry.'" class="scat__pr-mainimg" alt="'.$v->name.'" /><div class="scat__pr-imgqw"></div></div>';
								$class = 'class="scat__qv-imgactive"';
							}
				    		$img_html .= '<img src="'.$path['baseurl'].'/scat_products/'.$v->id.'/'.$entry.'" '.$class.' alt="'.$v->name.'" />';
							$i++;	
						}
						$enter_img++;
				    }

					if ($enter_img == 0) {
						$url = plugins_url();
						?>
						<img src="<?php echo $url.'/scatalog/images/no_photo.svg'; ?>" alt="<?php echo __('No photo', 'no photo'); ?>" title="<?php echo $entry; ?>" />
						<?php
					}
				if ( $enter_img > 1 ) {
					echo '<div class="scat__pr-thumb">'. $img_html.'</div>';
				}
				?>
				<div class="scat__qv-wrap">
					<div class="scat__qv-shadow"></div>
					<div class="scat__qv-form">
						<div class="scat__qv-content">
							<div class="scat__qv-close"></div>
								<div class="scat__qv-images">
								<?php echo $img_html; ?>
								</div>
								<div class="scat__qv-buttons">
									<div class="scat__qv-next"></div>
									<div class="scat__qv-prev"></div>
								</div>
						</div>
					</div>
				</div>
			</div>
			<div class="scat__pr-area"> 
				<?php if ($v->new_price > 0) { ?>
				<div class="scat__pr-pricebox">
					<p class="old-price"><?php echo $settings['currency'].$v->price ?></p>
					<p class="special-price"><?php echo $settings['currency'].$v->new_price ?></p>
				</div>
				<?php } else { ?>
				<div class="scat__pr-pricebox">
					<p class="regular-price"><?php echo $settings['currency'].$v->price ?></p>
				</div>
				<?php } ?>

				<div class="scat__pr-param">
					<b class="param-title "><?php echo __('Availability', 'availability'); ?>: </b>
					<span class="scat__pr-avaib <?php if ($v->in_stock == 1) echo 'par-in-stock'; ?>"><?php echo $v->in_stock == 1 ? __('In stock', 'in stock') : __('Out of stock', 'out of stock') ?></span>
				</div>

				<?php
					if ( $arr[0]->params != 'N;' ) {
						//show additional parameters
						$arr_param = unserialize($arr_data[0]->params);
						foreach ($arr_val as $key => $val) {
							if ( $val['hide'] != '1' ) {
								echo '<div class="scat__pr-param">';
								$html = '';
								switch ( $val['type'] ) {
									case 0: $html = $arr_param[$val['id']]; break;
									case 1: $html = $arr_param[$val['id']]; break;
									case 2: $html = product_data::get_color_list_fr('param['.$val['id'].']', $arr_param[ $val['id'] ]); break;
									case 3: $html = product_data::get_param_list_fr('param['.$val['id'].']', $arr_param[ $val['id'] ]); break;
								}
								if ($html != '<ul></ul>') echo '<b class="param-title '.$disabled.'">'.$val['name'].': </b>'.$html;
								echo '</div>';
							}
						}
					}
				?>

				<div data-name="<?php echo $v->id ?>" style="display: none;">
				<?php echo '<a href="'.get_site_url().'/'.$settings['sproduct'].'?id='.$v->id.'">'.$v->name.'</a>'; ?>
				</div>
				<div style="display: none;" class="data-form">
					<input type="hidden" value="<?php echo plugin_dir_url(__FILE__); ?>" class="data_site-url" />
					<input type="hidden" value="<?php echo $settings['store_manager_email']; ?>" class="store_manager_email" />
				</div>
			</div>

			<?php
			if ( $settings['enable_add_to_cart'] == 1 ) {
				echo '<button class="scat__pr-btnorder" data-id="'.$id.'">'.$settings['button_text'].'</button>';
			}
			?>

			<div class="clearfix"></div>
			<div class="scat__pr-content">
				<ul>
					<li><?php echo __('Description', 'description'); ?></li>
				</ul>
				<?php echo $v->description; ?>
			</div>
			<div class="send"></div>
		</div>
		<?php
	}


	/*
	 * get plugin settings array 
	*/
	function get_settings()
	{
		global $wpdb;

		$param_arr = array();
		$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'posts` WHERE `post_mime_type`="sproduct" OR `post_mime_type`="scategory"');
		foreach ($arr as $k => $v) {
			$param_arr[$v->post_mime_type] = $v->post_name;
		}		

		$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_settings`');
		foreach ($arr[0] as $k => $v){
			$param_arr[$k] = $v;
		}
		return($param_arr);
	}


	/*
	 * get product quick view data 
	*/
	function quick_view($id)
	{
		global $wpdb;
		include 'product.php';

		$arr_val = product_data::get_product_param(0);
		$arr_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products WHERE `id`="'.$id.'"');

		//images
		$path = wp_upload_dir();
		$dir = opendir($path['basedir'].'/scat_products/'.$id);
		$enter_img = 0;

	    $img_html = '';
		$i = 0;
		$class = '';

	    while ($entry = readdir($dir)) {
	    	if ( $entry != '.' && $entry != '..' ) {
				$i == 0 ? $class = 'class="scat__qv-imgactive"' : $class = '';
	    		$img_html .= '<img src="'.$path['baseurl'].'/scat_products/'.$id.'/'.$entry.'" '.$class.' alt="'.$v->name.'" />';
				$i++;	
			}
	    }

		if ($i == 0) {
			$img_html .= '<img src="'.plugin_dir_url().'scatalog/images/no_photo.svg'.'" alt="'.__('No photo', 'no photo').'" class="scat__qv-imgactive" />';
		}
		$arr_data[0]->img = $img_html;
		$arr_data[0]->img_count = $i;
		
		//show additional parameters
		$html = '';
		$arr_param = unserialize($arr_data[0]->params);

		foreach ($arr_val as $key => $val) {
			if ( $val['hide'] != '1' ) {
				$html .= '<div class="scat__pr-param">';
				switch ( $val['type'] ) {
					case 0: $param = $arr_param[$val['id']]; break;
					case 1: $param = $arr_param[$val['id']]; break;
					case 2: $param = product_data::get_color_list_fr('param['.$val['id'].']', $arr_param[ $val['id'] ]); break;
					case 3: $param = product_data::get_param_list_fr('param['.$val['id'].']', $arr_param[ $val['id'] ]); break;
				}
				if ( $param != '<ul></ul>' ) $html .= '<b class="param-title '.$disabled.'">'.$val['name'].': </b>'.$param;
				$html .= '</div>';
			}
		}

		$arr_data[0]->addparam = $html;

		$v = $arr_data[0];
		return $v;
	}


	/*
	 * get category breadcrumbs
	*/
	function cat_breadcrumbs($id)
	{
		global $wpdb;
		$i = 0;

		$data_arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`="'.$id.'"');
		$arr[$i] = $data_arr[0];
		$parent = $data_arr[0]->parent;
		
		while ( $parent != 0 ) {
			$data_arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`="'.$parent.'"');
			$parent = $data_arr[0]->parent;
			$i++;
			$arr[$i] = $data_arr[0];
		}

		return $arr;
	}


	/*
	 * get quick view
	*/
	function quick_view_template()
	{
		include(WP_PLUGIN_DIR.'/scatalog/templates/quick_view.php');
	}


	/*
	 * get order popup
	*/
	function order_popup_template()
	{
		include(WP_PLUGIN_DIR.'/scatalog/templates/new_order.php');
	}
}
?>