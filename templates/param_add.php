<div class="wcb_page">
	<h2><?php echo __('Add parameter', 'add parameter'); ?></h2>

	<form action="" name="edit-param" enctype="multipart/form-data" method="post">
		<input name="action" type="hidden" value="add">
		<input name="id" type="hidden" value="<?php echo $_REQUEST['id']; ?>">
		<input name="submit" type="hidden" value="1">

		<b><?php echo __( 'Name', 'name' ); ?></b>
		<input name="name" type="text" value="">

		<?php /*
		<b><?php echo __( 'Parent', 'parent' ); ?></b>
		<select name="parent">
			<?php
			echo '<option value="0"></option>';
			foreach ($arr_param as $key => $value) { ?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
				<?php
					if ( is_array($value['child']) ) {
						param_data::get_arr_param($value['child'], 0, $arr[0]->id);
					}
			}
			?>
		</select>
		*/ ?>
		<input type="hidden" name="parent" />

		<b><?php echo __( 'Hide', 'hide' ); ?></b>
		<?php 
			if ( $arr[0]->hide == '1' ) $is_hide = 'checked';
			else  $is_hide = '';
		?>
		<input name="hide" type="checkbox" value="1" <?php echo $is_hide; ?> />

		<b><?php echo __( 'Type', 'type' ); ?></b>
		<select name="type">
			<option value="<?php echo $pr_type[0]->id; ?>"><?php echo $pr_type[0]->name; ?></option>
			<?php
				foreach ($arr_types as $key => $value) {
					if ( $value->id != $pr_type[0]->id ) {
						echo '<option value="'.$value->id.'">'.$value->name.'</option>';
					}
				} 
			?>
		</select>

 		<input type="submit" value="<?php echo __( 'Save', 'save' ); ?>">
	</form>
</div>