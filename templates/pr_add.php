<div class="wcb_page">	<h2><?php echo __('Add product', 'add product'); ?></h2>	<form action="" name="new-user" enctype="multipart/form-data" method="post">		<input name="page" type="hidden" value="door_edit_page">		<input name="action" type="hidden" value="add">		<input name="id" type="hidden" value="">		<input name="submit" type="hidden" value="1">
		<b><?php echo __('Name', 'name'); ?></b>		<input name="name" type="text" value="">
		<b><?php echo __( 'Description', 'description' ); ?></b>		<textarea name="description"></textarea>
		<b><?php echo __( 'Category', 'category' ); ?></b>		<?php echo product_data::get_cat_list_select(); ?>
		<b><?php echo __( 'Price', 'price' ); ?></b>		<input name="price" type="text" class="sm-input" value="">
		<b><?php echo __( 'Sale price', 'sale price' ); ?></b>		<input name="new_price" type="text" class="sm-input" value="">		<b><?php echo __('In stock', 'in stock'); ?></b>		<input name="in_stock" type="checkbox" class="sm-input" value="1">		<b><?php echo __('Hide', 'hide'); ?></b>		<input name="hide" type="checkbox" class="sm-input" value="1">		<?php			//show additional parameters			if ( is_array($arr[0]->params) ) {				$arr_param = unserialize($arr[0]->params);				foreach ($arr_val as $key => $val) {					if ($val['hide'] == '1') $disabled = 'disabled';					else $disabled = '';						echo '<b class="param-title '.$disabled.'">'.$val['name'].'</b>';					switch ( $val['type'] ) {						case 0: echo '<input name="param['.$val['id'].']" type="text" class="sm-input" value="'.$arr_param[ $val['id'] ].'" '.$disabled.'>'; break;						case 1: echo '<input name="param['.$val['id'].']" type="number" class="sm-input" value="'.$arr_param[ $val['id'] ].'" '.$disabled.'>'; break;						case 2: echo product_data::get_color_list('param['.$val['id'].']', $arr_param[ $val['id'] ]); break;						case 3: echo product_data::get_param_list('param['.$val['id'].']', $arr_param[ $val['id'] ]); break;					}						}			}		?>
		<b><?php echo __( 'Photo', 'photo' ); ?></b>		<input name="photo" type="file" value="">		<div class="pr-images">			<?php product_data::get_pr_images($arr[0]->id, $dir); ?>		</div>		<input type="submit" value="<?php echo __( 'Add', 'add' ); ?>">
	</form>
</div>