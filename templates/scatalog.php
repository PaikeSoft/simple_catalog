<div class="wcb_page">
	<h2><?php echo __('Simple catalog', 'simple_catalog')?></h2>
	<form action="" name="cat-edit" enctype="multipart/form-data" method="post">
		<input name="action" type="hidden" value="edit">
		<input name="submit" type="hidden" value="1">

		<b><?php echo __('Category page link', 'cat_page_url'); ?></b>
		<input name="category_page" type="text" value="<?php echo $category_page; ?>">

		<b><?php echo __('Product page link', 'pr_page_url'); ?></b>
		<input name="product_page" type="text" value="<?php echo $product_page; ?>">

		<b><?php echo __('Enable product page', 'enable_product_page'); ?></b>
		<input name="enable_pr_page" type="checkbox" value="1" <?php echo $enable_pr_page;?>>

		<b><?php echo __('Enable quick view', 'enable_quick_ciew'); ?></b>
		<input name="enable_quick_view" type="checkbox" value="1" <?php echo $enable_quick_view;?>>

		<b><?php echo __( 'Currency', 'currency_code' ); ?></b>
		<input name="currency" type="text" value="<?php echo $arr[0]->currency; ?>">

 		<input type="submit" value="<?php echo __( 'Save', 'save' ); ?>">
	</form>
</div>