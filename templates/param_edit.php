<div class="wcb_page">
	<h2><?php echo __('Edit parameter', 'edit parameter'); ?></h2>

	<form action="" name="edit-param" enctype="multipart/form-data" method="post">
		<input name="action" type="hidden" value="edit">
		<input name="id" type="hidden" value="<?php echo $_REQUEST['id']; ?>">
		<input name="submit" type="hidden" value="1">

		<b><?php echo __( 'Name', 'name' ); ?></b>
		<input name="name" type="text" value="<?php echo $arr[0]->name; ?>">

		<?php /*
		<b><?php echo __( 'Parent', 'parent' ); ?></b>
		<select name="parent">
			<?php
			if (isset($parent[0]->id)) {
				echo '<option value="'.$parent[0]->id.'">'.$parent[0]->name.'</option>';
				echo '<option value="0"></option>';
			}
			else {
				echo '<option value="0"></option>';
			}
			foreach ($arr_param as $key => $value) { ?>
				<?php
				if ( $value['id'] != $arr[0]->id && $value['id'] != $parent[0]->id ) {
				?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
				<?php
					if ( is_array($value['child']) ) {
						param_data::get_arr_param($value['id'], 0, $arr[0]->id);
					}
				}
			}
			?>
		</select>
		*/ ?>

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