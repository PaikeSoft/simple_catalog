<div class="wcb_page">	<h2><?php echo __('Edit category', 'edit category'); ?></h2>	<form action="" name="cat-edit" enctype="multipart/form-data" method="post">		<input name="action" type="hidden" value="edit">		<input name="id" type="hidden" value="<?php echo $_REQUEST['id']; ?>">		<input name="submit" type="hidden" value="1">		<b><?php echo __( 'Name', 'name' ); ?></b>		<input name="name" type="text" value="<?php echo $arr[0] -> name; ?>">		<b><?php echo __( 'Parent', 'parent' ); ?></b>		<?php category_data::get_parent($arr[0]->id, $arr[0]->parent); ?>		<b><?php echo __( 'Description', 'description' ); ?></b>		<textarea name="descr"><?php echo $arr[0] -> descr; ?></textarea>
		<b><?php echo __( 'Photo', 'photo' ); ?></b>		<input name="photo" type="file" value="">		<div class="pr-images">			<?php category_data::get_cat_images($arr[0]->id, $dir); ?>		</div> 		<input type="submit" value="<?php echo __( 'Save', 'save' ); ?>">
	</form>
</div>