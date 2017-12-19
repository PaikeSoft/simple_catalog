<?php/*Plugin Name: Simple catalogVersion: 1.0Author: vokAuthor URI: http://paike.s-host.net*/?><?php//Function activate pluginregister_activation_hook( __FILE__, 'activate' ); //Function deactivate pluginregister_deactivation_hook( __FILE__, 'deactivate' ); //Function uninstall pluginregister_uninstall_hook( __FILE__, 'uninstall' );//create admin menuadd_action('admin_menu', 'CreatePluginMenu');//add style and css to "backend"if ( is_admin() ) {	wp_register_style('wcbAdminStyleSheets', WP_PLUGIN_URL.'/scatalog/css/admin.css');    wp_enqueue_style('wcbAdminStyleSheets');	add_action('wp_print_scripts', 'sca_load_scripts');}//add style and css to "frontend"else{	wp_register_style('wcbPageStyleSheets', WP_PLUGIN_URL.'/scatalog/css/style.css');    wp_enqueue_style('wcbPageStyleSheets');	add_action('wp_print_scripts', 'sc_load_scripts');}/*------------------------- show on site page -------------------------*//* * [scatalog page="main_categories"] - show main categories list * [scatalog page="categories" id="#"] - show categories list * [scatalog page="category" id="#"] - show product list * [scatalog page="product" id="#"] - show product * id="#" - for show one item*/add_shortcode('scatalog', 'site_show_catalog');function site_show_catalog ($attr){	global $wpdb;	include 'classes/frontend.php';	include 'classes/product.php';	echo '<div class="scat-wrap">';	switch ( $attr['page'] ) {		case 'main_categories': 				$arr = frontend::get_list("");				frontend::main_categories($arr);				break;		case 'categories': 				$arr = frontend::get_category($attr[id]);				frontend::categories($arr, 1);				break;		case 'category': 				frontend::category($attr[id]); 				break;		case 'product': 				$arr_val = product_data::get_product_param(0);				frontend::product($attr[id], $arr_val); 				break;	}	echo '</div>';}/* * add scripts on frontend page*/function sc_load_scripts(){	wp_register_script('scatalogJs', WP_PLUGIN_URL.'/scatalog/js/scripts.js' );	wp_enqueue_script('scatalogJs');}/* * add scripts on admin page*/function sca_load_scripts(){	wp_register_script('colorJs', WP_PLUGIN_URL.'/scatalog/js/jscolor.js' );	wp_enqueue_script('colorJs');}/*------------------------- show on admin page -------------------------*/function CreatePluginMenu(){    if (function_exists('add_options_page'))    {		add_menu_page( __( 'Simple catalog', 'scatalog' ), __( 'SCatalog', 'scatalog' ), 'manage_options', 'scatalog', 'scatalog', plugins_url( 'scatalog/images/catalog.png' ), 6);		add_submenu_page( 'scatalog', __( 'Categories', 'categories' ), __( 'Categories', 'categories' ), 'manage_options', 'scategories', 'scategoriesPageOptions');		add_submenu_page( 'scatalog', __( 'Products', 'products' ), __( 'Products', 'products' ), 'manage_options', 'sproducts', 'sproductsPageOptions');		add_submenu_page( 'scatalog', __( 'Parameters', 'parameters' ), __( 'Parameters', 'parameters' ), 'manage_options', 'sparameters', 'sparametersPageOptions');    }}/*------------------------- Start plugin admin page -------------------------*//* * get main parameters for plugin*/function scatalog(){	global $wpdb;	$enable_pr_page = 0;	if (isset($_REQUEST['enable_pr_page'])) $enable_pr_page = '1';	$enable_quick_view = 0;	if (isset($_REQUEST['enable_quick_view'])) $enable_quick_view = '1';	$enable_add_to_cart = 0;	if (isset($_REQUEST['enable_add_to_cart'])) $enable_add_to_cart = '1';	//update settings	if ( isset($_REQUEST['submit']) ) {		$wpdb->query('UPDATE `'.$wpdb->prefix.'posts` SET `post_name`="'.addslashes($_REQUEST['product_page']).'" WHERE `post_mime_type`="sproduct"');		$wpdb->query('UPDATE `'.$wpdb->prefix.'posts` SET `post_name`="'.addslashes($_REQUEST['category_page']).'" WHERE `post_mime_type`="scategory"');		$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_settings` SET `enable_pr_page`="'.$enable_pr_page.'", `enable_quick_view`="'.$enable_quick_view.'", `enable_add_to_cart`="'.$enable_add_to_cart.'", `store_manager_email`="'.addslashes($_REQUEST['store_manager_email']).'", `currency`="'.addslashes($_REQUEST['currency']).'", `button_text`="'.addslashes($_REQUEST['button_text']).'"');	}	//get url links for category and product page	$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'posts` WHERE `post_mime_type`="scategory"');	$category_page = $arr[0]->post_name;	$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'posts` WHERE `post_mime_type`="sproduct"');	$product_page = $arr[0]->post_name;	//read settings table	$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_settings');	$enable_pr_page = 0;	if ($arr[0]->enable_pr_page == 1) $enable_pr_page = 'checked';	$enable_quick_view = 0;	if ($arr[0]->enable_quick_view == 1) $enable_quick_view = 'checked';	$enable_add_to_cart = 0;	if ($arr[0]->enable_add_to_cart == 1) $enable_add_to_cart = 'checked';	include 'templates/scatalog.php';}/*------------------------- Categories -------------------------*//* * functions for add, edit, delete category page*/function scategoriesPageOptions(){	global $wpdb;	$action = isset($_GET['action']) ? $_GET['action'] : null;	include 'classes/category.php';	switch ( $action ) {		case 'add':			if ( empty($_REQUEST['submit']) ) {				include 'templates/cat_add.php';			}			else {				$arr_max = $wpdb->get_results('SELECT max(`id`) as `id` FROM `'.$wpdb->prefix.'scat_cat`');							$max_el = $arr_max[0]->id + 1;				$wpdb->query('INSERT INTO `'.$wpdb->prefix.'scat_cat` (`id`, `name`, `descr`, `parent`, `sort_order`) VALUES ("'.$max_el.'", "'.addslashes($_REQUEST['name']).'", "'.addslashes($_REQUEST['descr']).'", "'.addslashes($_REQUEST['parent']).'", "'.$max_el.'")');				if ( $_FILES['photo']['name'] != '' ) {					$dir = wp_upload_dir();					category_data::upload_photo($_FILES, $dir, $max_el);				}				$arr = category_data::get_list("");				include('templates/cat_all.php');			}		break;		case 'delete':			$wpdb->query('DELETE FROM `'.$wpdb->prefix.'scat_cat` WHERE `id`="'.$_REQUEST['id'].'"');			$arr = category_data::get_list("");			include('templates/cat_all.php');		break;		case 'edit':			$dir = wp_upload_dir();			if ( !empty($_REQUEST['submit']) ) {				//update data				$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_cat` SET `name` = "'.addslashes($_REQUEST['name']).'", `descr` = "'.addslashes($_REQUEST['descr']).'",  `parent` = "'.addslashes($_REQUEST['parent']).'" WHERE `id` = "'.$_REQUEST['id'].'"');				if ( $_FILES['photo']['name'] != '' ) {					category_data::upload_photo($_FILES, $dir, $_REQUEST['id']);				}			}			else {				if ( !empty($_REQUEST['del_img']) && file_exists($dir['basedir'].'/scat_category/'.$_REQUEST['id'].'/'.$_REQUEST['del_img']) ) {					unlink($dir['basedir'].'/scat_category/'.$_REQUEST['id'].'/'.$_REQUEST['del_img']);				}			}			$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_cat WHERE `id`="'.$_REQUEST['id'].'"');			include 'templates/cat_edit.php';			echo $html;		break;		default:			$arr = category_data::get_list("");			include('templates/cat_all.php');		break;	}}/*------------------------- products -------------------------*//* * functions for add, edit, delete product page*/function sproductsPageOptions(){	global $wpdb;	$action = isset($_GET['action']) ? $_GET['action'] : null ;	include 'classes/product.php';	switch ( $action ) {		case 'add':			if ( empty($_REQUEST['submit']) ) {				$arr_val = product_data::get_product_param(0);				include 'templates/pr_add.php';			}			else {				$arr_max = $wpdb->get_results('SELECT max(`id`) as `id` FROM `'.$wpdb->prefix.'scat_products`');				$max_el = intval($arr_max[0]->id) + 1;				if ( $_REQUEST['scat_cat'] != '' ) {$cat_str = implode(',', $_REQUEST['scat_cat']).',';}				else {$cat_str = '';}								$in_stock = $_REQUEST['in_stock'] ? 1 : 0;				$hide = $_REQUEST['hide'] ? 1 : 0;				$_REQUEST['name'] == '' ? $pr_name = 'Product' : $pr_name = $_REQUEST['name'];				$wpdb->query('INSERT INTO `'.$wpdb->prefix.'scat_products` (`name`,`description`,`cat_id`,`price`,`new_price`,`params`,`in_stock`,`hide`) VALUES ("'.addslashes($pr_name).'","'.addslashes($_REQUEST['description']).'","'.$cat_str.'","'.addslashes($_REQUEST['price']).'","'.addslashes($_REQUEST['new_price']).'","'.addslashes(serialize($_REQUEST['param'])).'","'.$in_stock.'","'.$hide.'")');				if ( $_FILES['photo']['name'] != '' ) {					$dir = wp_upload_dir();					product_data::upload_photo($_FILES, $dir, $max_el);				}				$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products ORDER BY `cat_id`,`sort_order`');				include('templates/pr_all.php');			}		break;		case 'delete':			$wpdb->query('DELETE FROM `'.$wpdb->prefix.'scat_products` WHERE `id`="'.$_REQUEST['id'].'"');			$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products ORDER BY `cat_id`,`sort_order`');			include('templates/pr_all.php');		break;		case 'edit':			$dir = wp_upload_dir();			if ( !empty($_REQUEST['submit']) ) {				//upload Photo				if ( $_FILES['photo']['name'] != '' ) {					product_data::upload_photo($_FILES, $dir, $_REQUEST['id']);				}				$cat_str = implode(',', $_REQUEST['scat_cat']).',';				$in_stock = $_REQUEST['in_stock'] ? 1 : 0;				$hide = $_REQUEST['hide'] ? 1 : 0;				//update data				$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_products` SET `name` = "'.addslashes($_REQUEST['name']).'", `description` = "'.addslashes($_REQUEST['description']).'", `cat_id` = "'.$cat_str.'", `price` = "'.addslashes($_REQUEST['price']).'", `new_price` = "'.addslashes($_REQUEST['new_price']).'", `params` = "'.addslashes(serialize($_REQUEST['param'])).'", `in_stock`="'.$in_stock.'", `hide`="'.$hide.'" WHERE `id` = "'.$_REQUEST['id'].'"');			}			else {				if ( !empty($_REQUEST['del_img']) ) {					unlink($dir['basedir'].'/scat_products/'.$_REQUEST['id'].'/'.$_REQUEST['del_img']);				}			}			$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products WHERE `id`="'.$_REQUEST['id'].'"');			$arr_val = product_data::get_product_param(0);			include('templates/pr_edit.php');		break;		default:			$arr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'scat_products ORDER BY `cat_id`,`name`');			include('templates/pr_all.php');		break;	}}/*------------------------- parameters -------------------------*//* * functions for add, edit, delete additional parameters for product*/function sparametersPageOptions(){	global $wpdb;	$action = isset($_GET['action']) ? $_GET['action'] : null;	include 'classes/param.php';	include 'classes/product.php';	switch ( $action ) {		case 'add':			if ( empty($_REQUEST['submit']) ) {				//create array of products parameters (ALL)					$arr_param = product_data::get_product_param(0);				//get product type and types array				$pr_type = param_data::get_param_type($arr[0]->type);				$arr_types = param_data::get_param_type();								include 'templates/param_add.php';			}			else {				$arr = $wpdb->get_results('SELECT MAX(`id`) as `id` FROM `'.$wpdb->prefix.'scat_param`');				$max_el = $arr[0]->id + 1;				if ( isset($_REQUEST['hide']) ) $hide = $_REQUEST['hide'];				$hide = '0';				$wpdb->query('INSERT INTO `'.$wpdb->prefix.'scat_param` (`id`, `name`, `parent`,`sort_order`,`hide`,`type`) VALUES ("'.$max_el.'","'.addslashes($_REQUEST['name']).'", "'.addslashes($_REQUEST['parent']).'","'.$max_el.'", "'.$hide.'", "'.addslashes($_REQUEST['type']).'")');				$arr = product_data::get_product_param(0);				include('templates/param_all.php');			}		break;		case 'del':			$wpdb->query('DELETE FROM `'.$wpdb->prefix.'scat_param` WHERE `id`="'.$_REQUEST['id'].'" OR `parent`="'.$_REQUEST['id'].'"');			$arr = product_data::get_product_param(0);			include('templates/param_all.php');		break;		case 'edit':			if ( isset($_REQUEST['hide']) ) $hide = '1';			else $hide = '0';			if ( !empty($_REQUEST['submit']) ) {				$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_param` SET `name` = "'.addslashes($_REQUEST['name']).'", `parent`="'.addslashes($_REQUEST['parent']).'", `type`="'.addslashes($_REQUEST['type']).'", `hide`="'.$hide.'" WHERE `id` = "'.$_REQUEST['id'].'"');			}			$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_param` WHERE `id` = "'.$_REQUEST['id'].'"');			//create array of products parameters (ALL)			$arr_param = product_data::get_product_param(0);			//get product type and types array			$pr_type = param_data::get_param_type($arr[0]->type);			$arr_types = param_data::get_param_type();			include('templates/param_edit.php');		break;		case 'up':			$arr_min = $wpdb->get_results('SELECT min(`id`) as `id` FROM `'.$wpdb->prefix.'scat_param`');			$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_param` WHERE `id` = "'.$_REQUEST['id'].'"');			$sort = $arr[0]->sort_order - 1;			$arr_next = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_param` WHERE `sort_order` = "'.$sort.'"');			if ( $sort >= $arr_min[0]->id ) {				$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_param` SET `sort_order`="'.$sort.'" WHERE `id`="'.$_REQUEST['id'].'"');				$sort = $arr_next[0]->sort_order + 1;				$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_param` SET `sort_order`="'.$sort.'" WHERE `id`="'.$arr_next[0]->id.'"');			}			$arr = product_data::get_product_param(0);			include('templates/param_all.php');		break;		case 'down':			$arr_max = $wpdb->get_results('SELECT max(`id`) as `id` FROM `'.$wpdb->prefix.'scat_param`');			$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_param` WHERE `id` = "'.$_REQUEST['id'].'"');			$sort = $arr[0]->sort_order + 1;			$arr_next = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_param` WHERE `sort_order` = "'.$sort.'"');			if ( $sort <= $arr_max[0]->id ) {				$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_param` SET `sort_order`="'.$sort.'" WHERE `id`="'.$_REQUEST['id'].'"');				$sort = $arr_next[0]->sort_order - 1;				$wpdb->query('UPDATE `'.$wpdb->prefix.'scat_param` SET `sort_order`="'.$sort.'" WHERE `id`="'.$arr_next[0]->id.'"');			}			$arr = product_data::get_product_param(0);			include('templates/param_all.php');		break;				default:			$arr = product_data::get_product_param(0);			include('templates/param_all.php');		break;	}}/*=== activate script ===*/function activate(){global $wpdb;require_once(ABSPATH . 'wp-admin/upgrade-functions.php');//Tables$sql = 'CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'scat_products` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,`description` mediumtext COLLATE utf8_unicode_ci NOT NULL,`price` int(11) NOT NULL,`new_price` int(11) NOT NULL,`sort_order` int(11) NOT NULL,`params` mediumtext COLLATE utf8_unicode_ci NOT NULL,`cat_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,`in_stock` int(11) NOT NULL,`hide` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';dbDelta($sql);$sql = 'CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'scat_cat` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(50) NOT NULL,`descr` mediumtext,`parent` int(11) NOT NULL,`hide` int(11) NOT NULL,`sort_order` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci';dbDelta($sql);$sql = 'CREATE TABLE `'.$wpdb->prefix.'scat_param` (`id` int(11) NOT NULL,`name` varchar(50) NOT NULL,`sort_order` int(11) NOT NULL,`parent` int(11) NOT NULL,`hide` int(11) NOT NULL,`type` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci';dbDelta($sql);$sql = 'CREATE TABLE `'.$wpdb->prefix.'scat_types` (`id` int(11) NOT NULL,`name` varchar(50) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci';dbDelta($sql);$sql = 'CREATE TABLE `'.$wpdb->prefix.'scat_settings` (`id` int(11) NOT NULL,`enable_pr_page` int(11) NOT NULL,`enable_quick_view` int(11) NOT NULL,`currency` varchar(10) NOT NULL,`enable_add_to_cart` int(11) NOT NULL,`store_manager_email` varchar(50) NOT NULL,`button_text` varchar(20) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci';dbDelta($sql);$sql = "INSERT INTO `".$wpdb->prefix."scat_settings` (`id`, `enable_pr_page`, `enable_quick_view`, `currency`, `enable_add_to_cart`) VALUES (1, 1, 1, '$', 0)";dbDelta($sql);$sql = "INSERT INTO `".$wpdb->prefix."posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES(1, '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '[scatalog page=\"category\"]', '', '', 'publish', 'closed', 'closed', '', 'category', '', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '', 0, '', 0, 'page', 'scategory', 0),(1, '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '[scatalog page=\"product\"]', '', '', 'publish', 'closed', 'closed', '', 'product', '', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '', 0, '', 0, 'page', 'sproduct', 0);";dbDelta($sql);$sql = "INSERT INTO `".$wpdb->prefix."scat_types` (`id`, `name`) VALUES (0, 'text'), (1, 'number'), (2, 'color'), (3, 'list')";dbDelta($sql);} /* * Delete plugin */function deactivate(){	return true;}/* * Remove plugin */function uninstall(){	global $wpdb;	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'scat_products');	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'scat_cat');	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'scat_param');	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'scat_types');	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'scat_settings');	$wpdb->query('DELETE FROM '.$wpdb->prefix.'posts WHERE `post_mime_type`="scategory" OR `post_mime_type`="sproduct"');}?>