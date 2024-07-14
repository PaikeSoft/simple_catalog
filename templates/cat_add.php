<div class="wcb_page">
	<h2><?php echo __('Add category', 'add category')?></h2>
	<form action="" name="cat-add" enctype="multipart/form-data" method="post">
		<input name="action" type="hidden" value="add">
		<input name="id" type="hidden" value="">
		<input name="submit" type="hidden" value="1">

		<b><?php echo __( 'Name', 'name' ); ?></b>
		<input name="name" type="text" value="">

		<b><?php echo __( 'Parent', 'parent' ); ?></b>
		<?php category_data::get_parent($arr[0]->id, $arr[0]->parent); ?>

		<b><?php echo __( 'Description', 'description' ); ?></b>
		<textarea name="descr"></textarea>

		<b><?php echo __( 'Photo', 'photo' ); ?></b>
		<input name="photo" type="file" value="">
		<div class="pr-images">
			<?php category_data::get_cat_images($arr[0]->id, $dir); ?>
		</div>

 		<input type="submit" value="<?php echo __( 'Add', 'add' ); ?>">
	</form>
</div>